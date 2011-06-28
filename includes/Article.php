<?php
/**
 * File for articles
 * @file
 */

/**
 * Class representing a MediaWiki article and history.
 *
 * See design.txt for an overview.
 * Note: edit user interface and cache support functions have been
 * moved to separate EditPage and HTMLFileCache classes.
 *
 * @internal documentation reviewed 15 Mar 2010
 */
class Article {
	/**@{{
	 * @private
	 */

	/**
	 * @var RequestContext
	 */
	protected $mContext;

	var $mContent;                    // !<
	var $mContentLoaded = false;      // !<
	var $mCounter = -1;               // !< Not loaded
	var $mDataLoaded = false;         // !<
	var $mIsRedirect = false;         // !<
	var $mLatest = false;             // !<
	var $mOldId;                      // !<
	var $mPreparedEdit = false;

	/**
	 * @var Title
	 */
	var $mRedirectedFrom = null;

	/**
	 * @var Title
	 */
	var $mRedirectTarget = null;

	/**
	 * @var mixed: boolean false or URL string
	 */
	var $mRedirectUrl = false;        // !<
	var $mRevIdFetched = 0;           // !<

	/**
	 * @var Revision
	 */
	var $mLastRevision = null;

	/**
	 * @var Revision
	 */
	var $mRevision = null;

	var $mTimestamp = '';             // !<
	var $mTitle;                      // !< Title object
	var $mTouched = '19700101000000'; // !<

	/**
	 * @var ParserOptions: ParserOptions object for $wgUser articles
	 */
	var $mParserOptions;

	/**
	 * @var ParserOutput
	 */
	var $mParserOutput;

	/**@}}*/

	/**
	 * Constructor and clear the article
	 * @param $title Title Reference to a Title object.
	 * @param $oldId Integer revision ID, null to fetch from request, zero for current
	 */
	public function __construct( Title $title, $oldId = null ) {
		// @todo FIXME: Does the reference play any role here?
		$this->mTitle =& $title;
		$this->mOldId = $oldId;
	}

	/**
	 * Create an Article object of the appropriate class for the given page.
	 *
	 * @param $title Title
	 * @param $context RequestContext
	 * @return Article object
	 */
	public static function newFromTitle( $title, RequestContext $context ) {
		if ( NS_MEDIA == $title->getNamespace() ) {
			// FIXME: where should this go?
			$title = Title::makeTitle( NS_FILE, $title->getDBkey() );
		}

		$page = null;
		wfRunHooks( 'ArticleFromTitle', array( &$title, &$page ) );
		if ( !$page ) {
			switch( $title->getNamespace() ) {
				case NS_FILE:
					$page = new ImagePage( $title );
					break;
				case NS_CATEGORY:
					$page = new CategoryPage( $title );
					break;
				default:
					$page = new Article( $title );
			}
		}
		$page->setContext( $context );

		return $page;
	}

	/**
	 * Constructor from an page id
	 * @param $id Int article ID to load
	 */
	public static function newFromID( $id ) {
		$t = Title::newFromID( $id );
		# @todo FIXME: Doesn't inherit right
		return $t == null ? null : new self( $t );
		# return $t == null ? null : new static( $t ); // PHP 5.3
	}

	/**
	 * Tell the page view functions that this view was redirected
	 * from another page on the wiki.
	 * @param $from Title object.
	 */
	public function setRedirectedFrom( Title $from ) {
		$this->mRedirectedFrom = $from;
	}

	/**
	 * If this page is a redirect, get its target
	 *
	 * The target will be fetched from the redirect table if possible.
	 * If this page doesn't have an entry there, call insertRedirect()
	 * @return Title|mixed object, or null if this page is not a redirect
	 */
	public function getRedirectTarget() {
		if ( !$this->mTitle->isRedirect() ) {
			return null;
		}

		if ( $this->mRedirectTarget !== null ) {
			return $this->mRedirectTarget;
		}

		# Query the redirect table
		$dbr = wfGetDB( DB_SLAVE );
		$row = $dbr->selectRow( 'redirect',
			array( 'rd_namespace', 'rd_title', 'rd_fragment', 'rd_interwiki' ),
			array( 'rd_from' => $this->getID() ),
			__METHOD__
		);

		// rd_fragment and rd_interwiki were added later, populate them if empty
		if ( $row && !is_null( $row->rd_fragment ) && !is_null( $row->rd_interwiki ) ) {
			return $this->mRedirectTarget = Title::makeTitle(
				$row->rd_namespace, $row->rd_title,
				$row->rd_fragment, $row->rd_interwiki );
		}

		# This page doesn't have an entry in the redirect table
		return $this->mRedirectTarget = $this->insertRedirect();
	}

	/**
	 * Insert an entry for this page into the redirect table.
	 *
	 * Don't call this function directly unless you know what you're doing.
	 * @return Title object or null if not a redirect
	 */
	public function insertRedirect() {
		// recurse through to only get the final target
		$retval = Title::newFromRedirectRecurse( $this->getRawText() );
		if ( !$retval ) {
			return null;
		}
		$this->insertRedirectEntry( $retval );
		return $retval;
	}

	/**
	 * Insert or update the redirect table entry for this page to indicate
	 * it redirects to $rt .
	 * @param $rt Title redirect target
	 */
	public function insertRedirectEntry( $rt ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->replace( 'redirect', array( 'rd_from' ),
			array(
				'rd_from' => $this->getID(),
				'rd_namespace' => $rt->getNamespace(),
				'rd_title' => $rt->getDBkey(),
				'rd_fragment' => $rt->getFragment(),
				'rd_interwiki' => $rt->getInterwiki(),
			),
			__METHOD__
		);
	}

	/**
	 * Get the Title object or URL this page redirects to
	 *
	 * @return mixed false, Title of in-wiki target, or string with URL
	 */
	public function followRedirect() {
		return $this->getRedirectURL( $this->getRedirectTarget() );
	}

	/**
	 * Get the Title object this text redirects to
	 *
	 * @param $text string article content containing redirect info
	 * @return mixed false, Title of in-wiki target, or string with URL
	 * @deprecated since 1.17
	 */
	public function followRedirectText( $text ) {
		// recurse through to only get the final target
		return $this->getRedirectURL( Title::newFromRedirectRecurse( $text ) );
	}

	/**
	 * Get the Title object or URL to use for a redirect. We use Title
	 * objects for same-wiki, non-special redirects and URLs for everything
	 * else.
	 * @param $rt Title Redirect target
	 * @return mixed false, Title object of local target, or string with URL
	 */
	public function getRedirectURL( $rt ) {
		if ( $rt ) {
			if ( $rt->getInterwiki() != '' ) {
				if ( $rt->isLocal() ) {
					// Offsite wikis need an HTTP redirect.
					//
					// This can be hard to reverse and may produce loops,
					// so they may be disabled in the site configuration.
					$source = $this->mTitle->getFullURL( 'redirect=no' );
					return $rt->getFullURL( 'rdfrom=' . urlencode( $source ) );
				}
			} else {
				if ( $rt->getNamespace() == NS_SPECIAL ) {
					// Gotta handle redirects to special pages differently:
					// Fill the HTTP response "Location" header and ignore
					// the rest of the page we're on.
					//
					// This can be hard to reverse, so they may be disabled.
					if ( $rt->isSpecial( 'Userlogout' ) ) {
						// rolleyes
					} else {
						return $rt->getFullURL();
					}
				}

				return $rt;
			}
		}

		// No or invalid redirect
		return false;
	}

	/**
	 * Get the title object of the article
	 * @return Title object of this page
	 */
	public function getTitle() {
		return $this->mTitle;
	}

	/**
	 * Clear the object
	 * @todo FIXME: Shouldn't this be public?
	 * @private
	 */
	public function clear() {
		$this->mDataLoaded    = false;
		$this->mContentLoaded = false;

		$this->mCounter = -1; # Not loaded
		$this->mRedirectedFrom = null; # Title object if set
		$this->mRedirectTarget = null; # Title object if set
		$this->mLastRevision = null; # Latest revision
		$this->mTimestamp = '';
		$this->mTouched = '19700101000000';
		$this->mIsRedirect = false;
		$this->mRevIdFetched = 0;
		$this->mRedirectUrl = false;
		$this->mLatest = false;
		$this->mPreparedEdit = false;
	}

	/**
	 * Note that getContent/loadContent do not follow redirects anymore.
	 * If you need to fetch redirectable content easily, try
	 * the shortcut in Article::followRedirect()
	 *
	 * This function has side effects! Do not use this function if you
	 * only want the real revision text if any.
	 *
	 * @return Return the text of this revision
	 */
	public function getContent() {
		global $wgUser;

		wfProfileIn( __METHOD__ );

		if ( $this->getID() === 0 ) {
			# If this is a MediaWiki:x message, then load the messages
			# and return the message value for x.
			if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
				$text = $this->mTitle->getDefaultMessageText();
				if ( $text === false ) {
					$text = '';
				}
			} else {
				$text = wfMsgExt( $wgUser->isLoggedIn() ? 'noarticletext' : 'noarticletextanon', 'parsemag' );
			}
			wfProfileOut( __METHOD__ );

			return $text;
		} else {
			$this->loadContent();
			wfProfileOut( __METHOD__ );

			return $this->mContent;
		}
	}

	/**
	 * Get the text of the current revision. No side-effects...
	 *
	 * @return Return the text of the current revision
	 */
	public function getRawText() {
		// Check process cache for current revision
		if ( $this->mContentLoaded && $this->mOldId == 0 ) {
			return $this->mContent;
		}

		$rev = Revision::newFromTitle( $this->mTitle );
		$text = $rev ? $rev->getRawText() : false;

		return $text;
	}

	/**
	 * Get the text that needs to be saved in order to undo all revisions
	 * between $undo and $undoafter. Revisions must belong to the same page,
	 * must exist and must not be deleted
	 * @param $undo Revision
	 * @param $undoafter Revision Must be an earlier revision than $undo
	 * @return mixed string on success, false on failure
	 */
	public function getUndoText( Revision $undo, Revision $undoafter = null ) {
		$cur_text = $this->getRawText();
		if ( $cur_text === false ) {
			return false; // no page
		}
		$undo_text = $undo->getText();
		$undoafter_text = $undoafter->getText();

		if ( $cur_text == $undo_text ) {
			# No use doing a merge if it's just a straight revert.
			return $undoafter_text;
		}

		$undone_text = '';

		if ( !wfMerge( $undo_text, $undoafter_text, $cur_text, $undone_text ) ) {
			return false;
		}

		return $undone_text;
	}

	/**
	 * @return int The oldid of the article that is to be shown, 0 for the
	 *             current revision
	 */
	public function getOldID() {
		if ( is_null( $this->mOldId ) ) {
			$this->mOldId = $this->getOldIDFromRequest();
		}

		return $this->mOldId;
	}

	/**
	 * Sets $this->mRedirectUrl to a correct URL if the query parameters are incorrect
	 *
	 * @return int The old id for the request
	 */
	public function getOldIDFromRequest() {
		global $wgRequest;

		$this->mRedirectUrl = false;

		$oldid = $wgRequest->getVal( 'oldid' );

		if ( isset( $oldid ) ) {
			$oldid = intval( $oldid );
			if ( $wgRequest->getVal( 'direction' ) == 'next' ) {
				$nextid = $this->mTitle->getNextRevisionID( $oldid );
				if ( $nextid ) {
					$oldid = $nextid;
				} else {
					$this->mRedirectUrl = $this->mTitle->getFullURL( 'redirect=no' );
				}
			} elseif ( $wgRequest->getVal( 'direction' ) == 'prev' ) {
				$previd = $this->mTitle->getPreviousRevisionID( $oldid );
				if ( $previd ) {
					$oldid = $previd;
				}
			}
		}

		if ( !$oldid ) {
			$oldid = 0;
		}

		return $oldid;
	}

	/**
	 * Load the revision (including text) into this object
	 */
	function loadContent() {
		if ( $this->mContentLoaded ) {
			return;
		}

		wfProfileIn( __METHOD__ );

		$this->fetchContent( $this->getOldID() );

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Return the list of revision fields that should be selected to create
	 * a new page.
	 */
	public static function selectFields() {
		return array(
			'page_id',
			'page_namespace',
			'page_title',
			'page_restrictions',
			'page_counter',
			'page_is_redirect',
			'page_is_new',
			'page_random',
			'page_touched',
			'page_latest',
			'page_len',
		);
	}

	/**
	 * Fetch a page record with the given conditions
	 * @param $dbr DatabaseBase object
	 * @param $conditions Array
	 * @return mixed Database result resource, or false on failure
	 */
	protected function pageData( $dbr, $conditions ) {
		$fields = self::selectFields();

		wfRunHooks( 'ArticlePageDataBefore', array( &$this, &$fields ) );

		$row = $dbr->selectRow( 'page', $fields, $conditions, __METHOD__ );

		wfRunHooks( 'ArticlePageDataAfter', array( &$this, &$row ) );

		return $row;
	}

	/**
	 * Fetch a page record matching the Title object's namespace and title
	 * using a sanitized title string
	 *
	 * @param $dbr DatabaseBase object
	 * @param $title Title object
	 * @return mixed Database result resource, or false on failure
	 */
	protected function pageDataFromTitle( $dbr, $title ) {
		return $this->pageData( $dbr, array(
			'page_namespace' => $title->getNamespace(),
			'page_title'     => $title->getDBkey() ) );
	}

	/**
	 * Fetch a page record matching the requested ID
	 *
	 * @param $dbr DatabaseBase
	 * @param $id Integer
	 * @return mixed Database result resource, or false on failure
	 */
	protected function pageDataFromId( $dbr, $id ) {
		return $this->pageData( $dbr, array( 'page_id' => $id ) );
	}

	/**
	 * Set the general counter, title etc data loaded from
	 * some source.
	 *
	 * @param $data Object|String $res->fetchObject() object or the string "fromdb" to reload
	 */
	public function loadPageData( $data = 'fromdb' ) {
		if ( $data === 'fromdb' ) {
			$dbr = wfGetDB( DB_SLAVE );
			$data = $this->pageDataFromTitle( $dbr, $this->mTitle );
		}

		$lc = LinkCache::singleton();

		if ( $data ) {
			$lc->addGoodLinkObj( $data->page_id, $this->mTitle, $data->page_len, $data->page_is_redirect, $data->page_latest );

			$this->mTitle->mArticleID = intval( $data->page_id );

			# Old-fashioned restrictions
			$this->mTitle->loadRestrictions( $data->page_restrictions );

			$this->mCounter     = intval( $data->page_counter );
			$this->mTouched     = wfTimestamp( TS_MW, $data->page_touched );
			$this->mIsRedirect  = intval( $data->page_is_redirect );
			$this->mLatest      = intval( $data->page_latest );
		} else {
			$lc->addBadLinkObj( $this->mTitle );
			$this->mTitle->mArticleID = 0;
		}

		$this->mDataLoaded = true;
	}

	/**
	 * Get text of an article from database
	 * Does *NOT* follow redirects.
	 *
	 * @param $oldid Int: 0 for whatever the latest revision is
	 * @return mixed string containing article contents, or false if null
	 */
	function fetchContent( $oldid = 0 ) {
		if ( $this->mContentLoaded ) {
			return $this->mContent;
		}

		# Pre-fill content with error message so that if something
		# fails we'll have something telling us what we intended.
		$t = $this->mTitle->getPrefixedText();
		$d = $oldid ? wfMsgExt( 'missingarticle-rev', array( 'escape' ), $oldid ) : '';
		$this->mContent = wfMsgNoTrans( 'missing-article', $t, $d ) ;

		if ( $oldid ) {
			$revision = Revision::newFromId( $oldid );
			if ( $revision === null ) {
				wfDebug( __METHOD__ . " failed to retrieve specified revision, id $oldid\n" );
				return false;
			}

			if ( !$this->mDataLoaded || $this->getID() != $revision->getPage() ) {
				$data = $this->pageDataFromId( wfGetDB( DB_SLAVE ), $revision->getPage() );

				if ( !$data ) {
					wfDebug( __METHOD__ . " failed to get page data linked to revision id $oldid\n" );
					return false;
				}

				$this->mTitle = Title::makeTitle( $data->page_namespace, $data->page_title );
				$this->loadPageData( $data );
			}
		} else {
			if ( !$this->mDataLoaded ) {
				$this->loadPageData();
			}

			if ( $this->mLatest === false ) {
				wfDebug( __METHOD__ . " failed to find page data for title " . $this->mTitle->getPrefixedText() . "\n" );
				return false;
			}

			$revision = Revision::newFromId( $this->mLatest );
			if ( $revision === null ) {
				wfDebug( __METHOD__ . " failed to retrieve current page, rev_id {$this->mLatest}\n" );
				return false;
			}
		}

		// @todo FIXME: Horrible, horrible! This content-loading interface just plain sucks.
		// We should instead work with the Revision object when we need it...
		$this->mContent   = $revision->getText( Revision::FOR_THIS_USER ); // Loads if user is allowed

		if ( $revision->getId() == $this->mLatest ) {
			$this->setLastEdit( $revision );
		}

		$this->mRevIdFetched = $revision->getId();
		$this->mContentLoaded = true;
		$this->mRevision =& $revision;

		wfRunHooks( 'ArticleAfterFetchContent', array( &$this, &$this->mContent ) );

		return $this->mContent;
	}

	/**
	 * No-op
	 * @deprecated since 1.18
	 */
	public function forUpdate() {
		wfDeprecated( __METHOD__ );
	}

	/**
	 * @return int Page ID
	 */
	public function getID() {
		return $this->mTitle->getArticleID();
	}

	/**
	 * @return bool Whether or not the page exists in the database
	 */
	public function exists() {
		return $this->getId() > 0;
	}

	/**
	 * Check if this page is something we're going to be showing
	 * some sort of sensible content for. If we return false, page
	 * views (plain action=view) will return an HTTP 404 response,
	 * so spiders and robots can know they're following a bad link.
	 *
	 * @return bool
	 */
	public function hasViewableContent() {
		return $this->exists() || $this->mTitle->isAlwaysKnown();
	}

	/**
	 * @return int The view count for the page
	 */
	public function getCount() {
		if ( -1 == $this->mCounter ) {
			$id = $this->getID();

			if ( $id == 0 ) {
				$this->mCounter = 0;
			} else {
				$dbr = wfGetDB( DB_SLAVE );
				$this->mCounter = $dbr->selectField( 'page',
					'page_counter',
					array( 'page_id' => $id ),
					__METHOD__
				);
			}
		}

		return $this->mCounter;
	}

	/**
	 * Determine whether a page would be suitable for being counted as an
	 * article in the site_stats table based on the title & its content
	 *
	 * @param $editInfo Object or false: object returned by prepareTextForEdit(),
	 *        if false, the current database state will be used
	 * @return Boolean
	 */
	public function isCountable( $editInfo = false ) {
		global $wgArticleCountMethod;

		if ( !$this->mTitle->isContentPage() ) {
			return false;
		}

		$text = $editInfo ? $editInfo->pst : false;

		if ( $this->isRedirect( $text ) ) {
			return false;
		}

		switch ( $wgArticleCountMethod ) {
		case 'any':
			return true;
		case 'comma':
			if ( $text === false ) {
				$text = $this->getRawText();
			}
			return strpos( $text,  ',' ) !== false;
		case 'link':
			if ( $editInfo ) {
				// ParserOutput::getLinks() is a 2D array of page links, so
				// to be really correct we would need to recurse in the array
				// but the main array should only have items in it if there are
				// links.
				return (bool)count( $editInfo->output->getLinks() );
			} else {
				return (bool)wfGetDB( DB_SLAVE )->selectField( 'pagelinks', 1,
					array( 'pl_from' => $this->getId() ), __METHOD__ );
			}
		}
	}

	/**
	 * Tests if the article text represents a redirect
	 *
	 * @param $text mixed string containing article contents, or boolean
	 * @return bool
	 */
	public function isRedirect( $text = false ) {
		if ( $text === false ) {
			if ( !$this->mDataLoaded ) {
				$this->loadPageData();
			}

			return (bool)$this->mIsRedirect;
		} else {
			return Title::newFromRedirect( $text ) !== null;
		}
	}

	/**
	 * Returns true if the currently-referenced revision is the current edit
	 * to this page (and it exists).
	 * @return bool
	 */
	public function isCurrent() {
		# If no oldid, this is the current version.
		if ( $this->getOldID() == 0 ) {
			return true;
		}

		return $this->exists() && $this->mRevision && $this->mRevision->isCurrent();
	}

	/**
	 * Loads everything except the text
	 * This isn't necessary for all uses, so it's only done if needed.
	 */
	protected function loadLastEdit() {
		if ( $this->mLastRevision !== null ) {
			return; // already loaded
		}

		# New or non-existent articles have no user information
		$id = $this->getID();
		if ( 0 == $id ) {
			return;
		}

		$revision = Revision::loadFromPageId( wfGetDB( DB_MASTER ), $id );
		if ( $revision ) {
			$this->setLastEdit( $revision );
		}
	}

	/**
	 * Set the latest revision
	 */
	protected function setLastEdit( Revision $revision ) {
		$this->mLastRevision = $revision;
		$this->mTimestamp = $revision->getTimestamp();
	}

	/**
	 * @return string GMT timestamp of last article revision
	 */
	public function getTimestamp() {
		// Check if the field has been filled by ParserCache::get()
		if ( !$this->mTimestamp ) {
			$this->loadLastEdit();
		}
		return wfTimestamp( TS_MW, $this->mTimestamp );
	}

	/**
	 * @param $audience Integer: one of:
	 *      Revision::FOR_PUBLIC       to be displayed to all users
	 *      Revision::FOR_THIS_USER    to be displayed to $wgUser
	 *      Revision::RAW              get the text regardless of permissions
	 * @return int user ID for the user that made the last article revision
	 */
	public function getUser( $audience = Revision::FOR_PUBLIC ) {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			return $this->mLastRevision->getUser( $audience );
		} else {
			return -1;
		}
	}

	/**
	 * @param $audience Integer: one of:
	 *      Revision::FOR_PUBLIC       to be displayed to all users
	 *      Revision::FOR_THIS_USER    to be displayed to $wgUser
	 *      Revision::RAW              get the text regardless of permissions
	 * @return string username of the user that made the last article revision
	 */
	public function getUserText( $audience = Revision::FOR_PUBLIC ) {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			return $this->mLastRevision->getUserText( $audience );
		} else {
			return '';
		}
	}

	/**
	 * @param $audience Integer: one of:
	 *      Revision::FOR_PUBLIC       to be displayed to all users
	 *      Revision::FOR_THIS_USER    to be displayed to $wgUser
	 *      Revision::RAW              get the text regardless of permissions
	 * @return string Comment stored for the last article revision
	 */
	public function getComment( $audience = Revision::FOR_PUBLIC ) {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			return $this->mLastRevision->getComment( $audience );
		} else {
			return '';
		}
	}

	/**
	 * Returns true if last revision was marked as "minor edit"
	 *
	 * @return boolean Minor edit indicator for the last article revision.
	 */
	public function getMinorEdit() {
		$this->loadLastEdit();
		if ( $this->mLastRevision ) {
			return $this->mLastRevision->isMinor();
		} else {
			return false;
		}
	}

	/**
	 * Use this to fetch the rev ID used on page views
	 *
	 * @return int revision ID of last article revision
	 */
	public function getRevIdFetched() {
		if ( $this->mRevIdFetched ) {
			return $this->mRevIdFetched;
		} else {
			return $this->getLatest();
		}
	}

	/**
	 * Get a list of users who have edited this article, not including the user who made
	 * the most recent revision, which you can get from $article->getUser() if you want it
	 * @return UserArrayFromResult
	 */
	public function getContributors() {
		# @todo FIXME: This is expensive; cache this info somewhere.

		$dbr = wfGetDB( DB_SLAVE );

		if ( $dbr->implicitGroupby() ) {
			$realNameField = 'user_real_name';
		} else {
			$realNameField = 'FIRST(user_real_name) AS user_real_name';
		}

		$tables = array( 'revision', 'user' );

		$fields = array(
			'rev_user as user_id',
			'rev_user_text AS user_name',
			$realNameField,
			'MAX(rev_timestamp) AS timestamp',
		);

		$conds = array( 'rev_page' => $this->getId() );

		// The user who made the top revision gets credited as "this page was last edited by
		// John, based on contributions by Tom, Dick and Harry", so don't include them twice.
		$user = $this->getUser();
		if ( $user ) {
			$conds[] = "rev_user != $user";
		} else {
			$conds[] = "rev_user_text != {$dbr->addQuotes( $this->getUserText() )}";
		}

		$conds[] = "{$dbr->bitAnd( 'rev_deleted', Revision::DELETED_USER )} = 0"; // username hidden?

		$jconds = array(
			'user' => array( 'LEFT JOIN', 'rev_user = user_id' ),
		);

		$options = array(
			'GROUP BY' => array( 'rev_user', 'rev_user_text' ),
			'ORDER BY' => 'timestamp DESC',
		);

		$res = $dbr->select( $tables, $fields, $conds, __METHOD__, $options, $jconds );
		return new UserArrayFromResult( $res );
	}

	/**
	 * This is the default action of the index.php entry point: just view the
	 * page of the given title.
	 */
	public function view() {
		global $wgUser, $wgOut, $wgRequest, $wgParser;
		global $wgUseFileCache, $wgUseETag;

		wfProfileIn( __METHOD__ );

		# Get variables from query string
		$oldid = $this->getOldID();

		# getOldID may want us to redirect somewhere else
		if ( $this->mRedirectUrl ) {
			$wgOut->redirect( $this->mRedirectUrl );
			wfDebug( __METHOD__ . ": redirecting due to oldid\n" );
			wfProfileOut( __METHOD__ );

			return;
		}

		$wgOut->setArticleFlag( true );
		# Set page title (may be overridden by DISPLAYTITLE)
		$wgOut->setPageTitle( $this->mTitle->getPrefixedText() );

		# If we got diff in the query, we want to see a diff page instead of the article.
		if ( $wgRequest->getCheck( 'diff' ) ) {
			wfDebug( __METHOD__ . ": showing diff page\n" );
			$this->showDiffPage();
			wfProfileOut( __METHOD__ );

			return;
		}

		# Allow frames by default
		$wgOut->allowClickjacking();

		$parserCache = ParserCache::singleton();

		$parserOptions = $this->getParserOptions();
		# Render printable version, use printable version cache
		if ( $wgOut->isPrintable() ) {
			$parserOptions->setIsPrintable( true );
			$parserOptions->setEditSection( false );
		} elseif ( $wgUseETag && !$this->mTitle->quickUserCan( 'edit' ) ) {
			$parserOptions->setEditSection( false );
		}

		# Try client and file cache
		if ( $oldid === 0 && $this->checkTouched() ) {
			if ( $wgUseETag ) {
				$wgOut->setETag( $parserCache->getETag( $this, $parserOptions ) );
			}

			# Is it client cached?
			if ( $wgOut->checkLastModified( $this->getTouched() ) ) {
				wfDebug( __METHOD__ . ": done 304\n" );
				wfProfileOut( __METHOD__ );

				return;
			# Try file cache
			} elseif ( $wgUseFileCache && $this->tryFileCache() ) {
				wfDebug( __METHOD__ . ": done file cache\n" );
				# tell wgOut that output is taken care of
				$wgOut->disable();
				$this->viewUpdates();
				wfProfileOut( __METHOD__ );

				return;
			}
		}

		if ( !$wgUseETag && !$this->mTitle->quickUserCan( 'edit' ) ) {
			$parserOptions->setEditSection( false );
		}

		# Should the parser cache be used?
		$useParserCache = $this->useParserCache( $oldid );
		wfDebug( 'Article::view using parser cache: ' . ( $useParserCache ? 'yes' : 'no' ) . "\n" );
		if ( $wgUser->getStubThreshold() ) {
			wfIncrStats( 'pcache_miss_stub' );
		}

		$wasRedirected = $this->showRedirectedFromHeader();
		$this->showNamespaceHeader();

		# Iterate through the possible ways of constructing the output text.
		# Keep going until $outputDone is set, or we run out of things to do.
		$pass = 0;
		$outputDone = false;
		$this->mParserOutput = false;

		while ( !$outputDone && ++$pass ) {
			switch( $pass ) {
				case 1:
					wfRunHooks( 'ArticleViewHeader', array( &$this, &$outputDone, &$useParserCache ) );
					break;
				case 2:
					# Try the parser cache
					if ( $useParserCache ) {
						$this->mParserOutput = $parserCache->get( $this, $parserOptions );

						if ( $this->mParserOutput !== false ) {
							wfDebug( __METHOD__ . ": showing parser cache contents\n" );
							$wgOut->addParserOutput( $this->mParserOutput );
							# Ensure that UI elements requiring revision ID have
							# the correct version information.
							$wgOut->setRevisionId( $this->mLatest );
							$outputDone = true;
						}
					}
					break;
				case 3:
					$text = $this->getContent();
					if ( $text === false || $this->getID() == 0 ) {
						wfDebug( __METHOD__ . ": showing missing article\n" );
						$this->showMissingArticle();
						wfProfileOut( __METHOD__ );
						return;
					}

					# Another whitelist check in case oldid is altering the title
					if ( !$this->mTitle->userCanRead() ) {
						wfDebug( __METHOD__ . ": denied on secondary read check\n" );
						$wgOut->loginToUse();
						$wgOut->output();
						$wgOut->disable();
						wfProfileOut( __METHOD__ );
						return;
					}

					# Are we looking at an old revision
					if ( $oldid && !is_null( $this->mRevision ) ) {
						$this->setOldSubtitle( $oldid );

						if ( !$this->showDeletedRevisionHeader() ) {
							wfDebug( __METHOD__ . ": cannot view deleted revision\n" );
							wfProfileOut( __METHOD__ );
							return;
						}

						# If this "old" version is the current, then try the parser cache...
						if ( $oldid === $this->getLatest() && $this->useParserCache( false ) ) {
							$this->mParserOutput = $parserCache->get( $this, $parserOptions );
							if ( $this->mParserOutput ) {
								wfDebug( __METHOD__ . ": showing parser cache for current rev permalink\n" );
								$wgOut->addParserOutput( $this->mParserOutput );
								$wgOut->setRevisionId( $this->mLatest );
								$outputDone = true;
								break;
							}
						}
					}

					# Ensure that UI elements requiring revision ID have
					# the correct version information.
					$wgOut->setRevisionId( $this->getRevIdFetched() );

					# Pages containing custom CSS or JavaScript get special treatment
					if ( $this->mTitle->isCssOrJsPage() || $this->mTitle->isCssJsSubpage() ) {
						wfDebug( __METHOD__ . ": showing CSS/JS source\n" );
						$this->showCssOrJsPage();
						$outputDone = true;
					} else {
						$rt = Title::newFromRedirectArray( $text );
						if ( $rt ) {
							wfDebug( __METHOD__ . ": showing redirect=no page\n" );
							# Viewing a redirect page (e.g. with parameter redirect=no)
							# Don't append the subtitle if this was an old revision
							$wgOut->addHTML( $this->viewRedirect( $rt, !$wasRedirected && $this->isCurrent() ) );
							# Parse just to get categories, displaytitle, etc.
							$this->mParserOutput = $wgParser->parse( $text, $this->mTitle, $parserOptions );
							$wgOut->addParserOutputNoText( $this->mParserOutput );
							$outputDone = true;
						}
					}
					break;
				case 4:
					# Run the parse, protected by a pool counter
					wfDebug( __METHOD__ . ": doing uncached parse\n" );

					$key = $parserCache->getKey( $this, $parserOptions );
					$poolArticleView = new PoolWorkArticleView( $this, $key, $useParserCache, $parserOptions );

					if ( !$poolArticleView->execute() ) {
						# Connection or timeout error
						wfProfileOut( __METHOD__ );
						return;
					} else {
						$outputDone = true;
					}
					break;
				# Should be unreachable, but just in case...
				default:
					break 2;
			}
		}

		# Adjust the title if it was set by displaytitle, -{T|}- or language conversion
		if ( $this->mParserOutput ) {
			$titleText = $this->mParserOutput->getTitleText();

			if ( strval( $titleText ) !== '' ) {
				$wgOut->setPageTitle( $titleText );
			}
		}

		# For the main page, overwrite the <title> element with the con-
		# tents of 'pagetitle-view-mainpage' instead of the default (if
		# that's not empty).
		# This message always exists because it is in the i18n files
		if ( $this->mTitle->equals( Title::newMainPage() ) ) {
			$msg = wfMessage( 'pagetitle-view-mainpage' )->inContentLanguage();
			if ( !$msg->isDisabled() ) {
				$wgOut->setHTMLTitle( $msg->title( $this->mTitle )->text() );
			}
		}

		# Now that we've filled $this->mParserOutput, we know whether
		# there are any __NOINDEX__ tags on the page
		$policy = $this->getRobotPolicy( 'view' );
		$wgOut->setIndexPolicy( $policy['index'] );
		$wgOut->setFollowPolicy( $policy['follow'] );

		$this->showViewFooter();
		$this->viewUpdates();
		wfProfileOut( __METHOD__ );
	}

	/**
	 * Show a diff page according to current request variables. For use within
	 * Article::view() only, other callers should use the DifferenceEngine class.
	 */
	public function showDiffPage() {
		global $wgRequest, $wgUser;

		$diff = $wgRequest->getVal( 'diff' );
		$rcid = $wgRequest->getVal( 'rcid' );
		$diffOnly = $wgRequest->getBool( 'diffonly', $wgUser->getOption( 'diffonly' ) );
		$purge = $wgRequest->getVal( 'action' ) == 'purge';
		$unhide = $wgRequest->getInt( 'unhide' ) == 1;
		$oldid = $this->getOldID();

		$de = new DifferenceEngine( $this->mTitle, $oldid, $diff, $rcid, $purge, $unhide );
		// DifferenceEngine directly fetched the revision:
		$this->mRevIdFetched = $de->mNewid;
		$de->showDiffPage( $diffOnly );

		if ( $diff == 0 || $diff == $this->getLatest() ) {
			# Run view updates for current revision only
			$this->viewUpdates();
		}
	}

	/**
	 * Show a page view for a page formatted as CSS or JavaScript. To be called by
	 * Article::view() only.
	 *
	 * This is hooked by SyntaxHighlight_GeSHi to do syntax highlighting of these
	 * page views.
	 */
	protected function showCssOrJsPage() {
		global $wgOut;

		$wgOut->wrapWikiMsg( "<div id='mw-clearyourcache'>\n$1\n</div>", 'clearyourcache' );

		// Give hooks a chance to customise the output
		if ( wfRunHooks( 'ShowRawCssJs', array( $this->mContent, $this->mTitle, $wgOut ) ) ) {
			// Wrap the whole lot in a <pre> and don't parse
			$m = array();
			preg_match( '!\.(css|js)$!u', $this->mTitle->getText(), $m );
			$wgOut->addHTML( "<pre class=\"mw-code mw-{$m[1]}\" dir=\"ltr\">\n" );
			$wgOut->addHTML( htmlspecialchars( $this->mContent ) );
			$wgOut->addHTML( "\n</pre>\n" );
		}
	}

	/**
	 * Get the robot policy to be used for the current view
	 * @param $action String the action= GET parameter
	 * @return Array the policy that should be set
	 * TODO: actions other than 'view'
	 */
	public function getRobotPolicy( $action ) {
		global $wgOut, $wgArticleRobotPolicies, $wgNamespaceRobotPolicies;
		global $wgDefaultRobotPolicy, $wgRequest;

		$ns = $this->mTitle->getNamespace();

		if ( $ns == NS_USER || $ns == NS_USER_TALK ) {
			# Don't index user and user talk pages for blocked users (bug 11443)
			if ( !$this->mTitle->isSubpage() ) {
				if ( Block::newFromTarget( null, $this->mTitle->getText() ) instanceof Block ) {
					return array(
						'index'  => 'noindex',
						'follow' => 'nofollow'
					);
				}
			}
		}

		if ( $this->getID() === 0 || $this->getOldID() ) {
			# Non-articles (special pages etc), and old revisions
			return array(
				'index'  => 'noindex',
				'follow' => 'nofollow'
			);
		} elseif ( $wgOut->isPrintable() ) {
			# Discourage indexing of printable versions, but encourage following
			return array(
				'index'  => 'noindex',
				'follow' => 'follow'
			);
		} elseif ( $wgRequest->getInt( 'curid' ) ) {
			# For ?curid=x urls, disallow indexing
			return array(
				'index'  => 'noindex',
				'follow' => 'follow'
			);
		}

		# Otherwise, construct the policy based on the various config variables.
		$policy = self::formatRobotPolicy( $wgDefaultRobotPolicy );

		if ( isset( $wgNamespaceRobotPolicies[$ns] ) ) {
			# Honour customised robot policies for this namespace
			$policy = array_merge(
				$policy,
				self::formatRobotPolicy( $wgNamespaceRobotPolicies[$ns] )
			);
		}
		if ( $this->mTitle->canUseNoindex() && is_object( $this->mParserOutput ) && $this->mParserOutput->getIndexPolicy() ) {
			# __INDEX__ and __NOINDEX__ magic words, if allowed. Incorporates
			# a final sanity check that we have really got the parser output.
			$policy = array_merge(
				$policy,
				array( 'index' => $this->mParserOutput->getIndexPolicy() )
			);
		}

		if ( isset( $wgArticleRobotPolicies[$this->mTitle->getPrefixedText()] ) ) {
			# (bug 14900) site config can override user-defined __INDEX__ or __NOINDEX__
			$policy = array_merge(
				$policy,
				self::formatRobotPolicy( $wgArticleRobotPolicies[$this->mTitle->getPrefixedText()] )
			);
		}

		return $policy;
	}

	/**
	 * Converts a String robot policy into an associative array, to allow
	 * merging of several policies using array_merge().
	 * @param $policy Mixed, returns empty array on null/false/'', transparent
	 *            to already-converted arrays, converts String.
	 * @return Array: 'index' => <indexpolicy>, 'follow' => <followpolicy>
	 */
	public static function formatRobotPolicy( $policy ) {
		if ( is_array( $policy ) ) {
			return $policy;
		} elseif ( !$policy ) {
			return array();
		}

		$policy = explode( ',', $policy );
		$policy = array_map( 'trim', $policy );

		$arr = array();
		foreach ( $policy as $var ) {
			if ( in_array( $var, array( 'index', 'noindex' ) ) ) {
				$arr['index'] = $var;
			} elseif ( in_array( $var, array( 'follow', 'nofollow' ) ) ) {
				$arr['follow'] = $var;
			}
		}

		return $arr;
	}

	/**
	 * If this request is a redirect view, send "redirected from" subtitle to
	 * $wgOut. Returns true if the header was needed, false if this is not a
	 * redirect view. Handles both local and remote redirects.
	 *
	 * @return boolean
	 */
	public function showRedirectedFromHeader() {
		global $wgOut, $wgRequest, $wgRedirectSources;

		$rdfrom = $wgRequest->getVal( 'rdfrom' );

		if ( isset( $this->mRedirectedFrom ) ) {
			// This is an internally redirected page view.
			// We'll need a backlink to the source page for navigation.
			if ( wfRunHooks( 'ArticleViewRedirect', array( &$this ) ) ) {
				$redir = Linker::link(
					$this->mRedirectedFrom,
					null,
					array(),
					array( 'redirect' => 'no' ),
					array( 'known', 'noclasses' )
				);

				$s = wfMsgExt( 'redirectedfrom', array( 'parseinline', 'replaceafter' ), $redir );
				$wgOut->setSubtitle( $s );

				// Set the fragment if one was specified in the redirect
				if ( strval( $this->mTitle->getFragment() ) != '' ) {
					$fragment = Xml::escapeJsString( $this->mTitle->getFragmentForURL() );
					$wgOut->addInlineScript( "redirectToFragment(\"$fragment\");" );
				}

				// Add a <link rel="canonical"> tag
				$wgOut->addLink( array( 'rel' => 'canonical',
					'href' => $this->mTitle->getLocalURL() )
				);

				return true;
			}
		} elseif ( $rdfrom ) {
			// This is an externally redirected view, from some other wiki.
			// If it was reported from a trusted site, supply a backlink.
			if ( $wgRedirectSources && preg_match( $wgRedirectSources, $rdfrom ) ) {
				$redir = Linker::makeExternalLink( $rdfrom, $rdfrom );
				$s = wfMsgExt( 'redirectedfrom', array( 'parseinline', 'replaceafter' ), $redir );
				$wgOut->setSubtitle( $s );

				return true;
			}
		}

		return false;
	}

	/**
	 * Show a header specific to the namespace currently being viewed, like
	 * [[MediaWiki:Talkpagetext]]. For Article::view().
	 */
	public function showNamespaceHeader() {
		global $wgOut;

		if ( $this->mTitle->isTalkPage() ) {
			if ( !wfMessage( 'talkpageheader' )->isDisabled() ) {
				$wgOut->wrapWikiMsg( "<div class=\"mw-talkpageheader\">\n$1\n</div>", array( 'talkpageheader' ) );
			}
		}
	}

	/**
	 * Show the footer section of an ordinary page view
	 */
	public function showViewFooter() {
		global $wgOut, $wgUseTrackbacks;

		# check if we're displaying a [[User talk:x.x.x.x]] anonymous talk page
		if ( $this->mTitle->getNamespace() == NS_USER_TALK && IP::isValid( $this->mTitle->getText() ) ) {
			$wgOut->addWikiMsg( 'anontalkpagetext' );
		}

		# If we have been passed an &rcid= parameter, we want to give the user a
		# chance to mark this new article as patrolled.
		$this->showPatrolFooter();

		# Trackbacks
		if ( $wgUseTrackbacks ) {
			$this->addTrackbacks();
		}

		wfRunHooks( 'ArticleViewFooter', array( $this ) );

	}

	/**
	 * If patrol is possible, output a patrol UI box. This is called from the
	 * footer section of ordinary page views. If patrol is not possible or not
	 * desired, does nothing.
	 */
	public function showPatrolFooter() {
		global $wgOut, $wgRequest, $wgUser;

		$rcid = $wgRequest->getVal( 'rcid' );

		if ( !$rcid || !$this->mTitle->quickUserCan( 'patrol' ) ) {
			return;
		}

		$token = $wgUser->editToken( $rcid );
		$wgOut->preventClickjacking();

		$wgOut->addHTML(
			"<div class='patrollink'>" .
				wfMsgHtml(
					'markaspatrolledlink',
					Linker::link(
						$this->mTitle,
						wfMsgHtml( 'markaspatrolledtext' ),
						array(),
						array(
							'action' => 'markpatrolled',
							'rcid' => $rcid,
							'token' => $token,
						),
						array( 'known', 'noclasses' )
					)
				) .
			'</div>'
		);
	}

	/**
	 * Show the error text for a missing article. For articles in the MediaWiki
	 * namespace, show the default message text. To be called from Article::view().
	 */
	public function showMissingArticle() {
		global $wgOut, $wgRequest, $wgUser;

		# Show info in user (talk) namespace. Does the user exist? Is he blocked?
		if ( $this->mTitle->getNamespace() == NS_USER || $this->mTitle->getNamespace() == NS_USER_TALK ) {
			$parts = explode( '/', $this->mTitle->getText() );
			$rootPart = $parts[0];
			$user = User::newFromName( $rootPart, false /* allow IP users*/ );
			$ip = User::isIP( $rootPart );

			if ( !$user->isLoggedIn() && !$ip ) { # User does not exist
				$wgOut->wrapWikiMsg( "<div class=\"mw-userpage-userdoesnotexist error\">\n\$1\n</div>",
					array( 'userpage-userdoesnotexist-view', wfEscapeWikiText( $rootPart ) ) );
			} elseif ( $user->isBlocked() ) { # Show log extract if the user is currently blocked
				LogEventsList::showLogExtract(
					$wgOut,
					'block',
					$user->getUserPage()->getPrefixedText(),
					'',
					array(
						'lim' => 1,
						'showIfEmpty' => false,
						'msgKey' => array(
							'blocked-notice-logextract',
							$user->getName() # Support GENDER in notice
						)
					)
				);
			}
		}

		wfRunHooks( 'ShowMissingArticle', array( $this ) );

		# Show delete and move logs
		LogEventsList::showLogExtract( $wgOut, array( 'delete', 'move' ), $this->mTitle->getPrefixedText(), '',
			array(  'lim' => 10,
				'conds' => array( "log_action != 'revision'" ),
				'showIfEmpty' => false,
				'msgKey' => array( 'moveddeleted-notice' ) )
		);

		# Show error message
		$oldid = $this->getOldID();
		if ( $oldid ) {
			$text = wfMsgNoTrans( 'missing-article',
				$this->mTitle->getPrefixedText(),
				wfMsgNoTrans( 'missingarticle-rev', $oldid ) );
		} elseif ( $this->mTitle->getNamespace() === NS_MEDIAWIKI ) {
			// Use the default message text
			$text = $this->mTitle->getDefaultMessageText();
		} else {
			$createErrors = $this->mTitle->getUserPermissionsErrors( 'create', $wgUser );
			$editErrors = $this->mTitle->getUserPermissionsErrors( 'edit', $wgUser );
			$errors = array_merge( $createErrors, $editErrors );

			if ( !count( $errors ) ) {
				$text = wfMsgNoTrans( 'noarticletext' );
			} else {
				$text = wfMsgNoTrans( 'noarticletext-nopermission' );
			}
		}
		$text = "<div class='noarticletext'>\n$text\n</div>";

		if ( !$this->hasViewableContent() ) {
			// If there's no backing content, send a 404 Not Found
			// for better machine handling of broken links.
			$wgRequest->response()->header( "HTTP/1.1 404 Not Found" );
		}

		$wgOut->addWikiText( $text );
	}

	/**
	 * If the revision requested for view is deleted, check permissions.
	 * Send either an error message or a warning header to $wgOut.
	 *
	 * @return boolean true if the view is allowed, false if not.
	 */
	public function showDeletedRevisionHeader() {
		global $wgOut, $wgRequest;

		if ( !$this->mRevision->isDeleted( Revision::DELETED_TEXT ) ) {
			// Not deleted
			return true;
		}

		// If the user is not allowed to see it...
		if ( !$this->mRevision->userCan( Revision::DELETED_TEXT ) ) {
			$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n",
				'rev-deleted-text-permission' );

			return false;
		// If the user needs to confirm that they want to see it...
		} elseif ( $wgRequest->getInt( 'unhide' ) != 1 ) {
			# Give explanation and add a link to view the revision...
			$oldid = intval( $this->getOldID() );
			$link = $this->mTitle->getFullUrl( "oldid={$oldid}&unhide=1" );
			$msg = $this->mRevision->isDeleted( Revision::DELETED_RESTRICTED ) ?
				'rev-suppressed-text-unhide' : 'rev-deleted-text-unhide';
			$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n",
				array( $msg, $link ) );

			return false;
		// We are allowed to see...
		} else {
			$msg = $this->mRevision->isDeleted( Revision::DELETED_RESTRICTED ) ?
				'rev-suppressed-text-view' : 'rev-deleted-text-view';
			$wgOut->wrapWikiMsg( "<div class='mw-warning plainlinks'>\n$1\n</div>\n", $msg );

			return true;
		}
	}

	/**
	 * Should the parser cache be used?
	 *
	 * @return boolean
	 */
	public function useParserCache( $oldid ) {
		global $wgUser, $wgEnableParserCache;

		return $wgEnableParserCache
			&& $wgUser->getStubThreshold() == 0
			&& $this->exists()
			&& empty( $oldid )
			&& !$this->mTitle->isCssOrJsPage()
			&& !$this->mTitle->isCssJsSubpage();
	}

	/**
	 * Execute the uncached parse for action=view
	 */
	public function doViewParse() {
		global $wgOut;

		$oldid = $this->getOldID();
		$parserOptions = $this->getParserOptions();

		# Render printable version, use printable version cache
		$parserOptions->setIsPrintable( $wgOut->isPrintable() );

		# Don't show section-edit links on old revisions... this way lies madness.
		if ( !$this->isCurrent() || $wgOut->isPrintable() || !$this->mTitle->quickUserCan( 'edit' ) ) {
			$parserOptions->setEditSection( false );
		}

		$useParserCache = $this->useParserCache( $oldid );
		$this->outputWikiText( $this->getContent(), $useParserCache, $parserOptions );

		return true;
	}

	/**
	 * Try to fetch an expired entry from the parser cache. If it is present,
	 * output it and return true. If it is not present, output nothing and
	 * return false. This is used as a callback function for
	 * PoolCounter::executeProtected().
	 *
	 * @return boolean
	 */
	public function tryDirtyCache() {
		global $wgOut;
		$parserCache = ParserCache::singleton();
		$options = $this->getParserOptions();

		if ( $wgOut->isPrintable() ) {
			$options->setIsPrintable( true );
			$options->setEditSection( false );
		}

		$output = $parserCache->getDirty( $this, $options );

		if ( $output ) {
			wfDebug( __METHOD__ . ": sending dirty output\n" );
			wfDebugLog( 'dirty', "dirty output " . $parserCache->getKey( $this, $options ) . "\n" );
			$wgOut->setSquidMaxage( 0 );
			$this->mParserOutput = $output;
			$wgOut->addParserOutput( $output );
			$wgOut->addHTML( "<!-- parser cache is expired, sending anyway due to pool overload-->\n" );

			return true;
		} else {
			wfDebugLog( 'dirty', "dirty missing\n" );
			wfDebug( __METHOD__ . ": no dirty cache\n" );

			return false;
		}
	}

	/**
	 * View redirect
	 *
	 * @param $target Title|Array of destination(s) to redirect
	 * @param $appendSubtitle Boolean [optional]
	 * @param $forceKnown Boolean: should the image be shown as a bluelink regardless of existence?
	 * @return string containing HMTL with redirect link
	 */
	public function viewRedirect( $target, $appendSubtitle = true, $forceKnown = false ) {
		global $wgOut, $wgStylePath;

		if ( !is_array( $target ) ) {
			$target = array( $target );
		}

		$imageDir = wfUILang()->getDir();

		if ( $appendSubtitle ) {
			$wgOut->appendSubtitle( wfMsgHtml( 'redirectpagesub' ) );
		}

		// the loop prepends the arrow image before the link, so the first case needs to be outside
		$title = array_shift( $target );

		if ( $forceKnown ) {
			$link = Linker::linkKnown( $title, htmlspecialchars( $title->getFullText() ) );
		} else {
			$link = Linker::link( $title, htmlspecialchars( $title->getFullText() ) );
		}

		$nextRedirect = $wgStylePath . '/common/images/nextredirect' . $imageDir . '.png';
		$alt = wfUILang()->isRTL() ? '←' : '→';
		// Automatically append redirect=no to each link, since most of them are redirect pages themselves.
		foreach ( $target as $rt ) {
			$link .= Html::element( 'img', array( 'src' => $nextRedirect, 'alt' => $alt ) );
			if ( $forceKnown ) {
				$link .= Linker::linkKnown( $rt, htmlspecialchars( $rt->getFullText(), array(), array( 'redirect' => 'no' ) ) );
			} else {
				$link .= Linker::link( $rt, htmlspecialchars( $rt->getFullText() ), array(), array( 'redirect' => 'no' ) );
			}
		}

		$imageUrl = $wgStylePath . '/common/images/redirect' . $imageDir . '.png';
		return '<div class="redirectMsg">' .
			Html::element( 'img', array( 'src' => $imageUrl, 'alt' => '#REDIRECT' ) ) .
			'<span class="redirectText">' . $link . '</span></div>';
	}

	/**
	 * Builds trackback links for article display if $wgUseTrackbacks is set to true
	 */
	public function addTrackbacks() {
		global $wgOut;

		$dbr = wfGetDB( DB_SLAVE );
		$tbs = $dbr->select( 'trackbacks',
			array( 'tb_id', 'tb_title', 'tb_url', 'tb_ex', 'tb_name' ),
			array( 'tb_page' => $this->getID() )
		);

		if ( !$dbr->numRows( $tbs ) ) {
			return;
		}

		$wgOut->preventClickjacking();

		$tbtext = "";
		foreach ( $tbs as $o ) {
			$rmvtxt = "";

			if ( $this->getContext()->getUser()->isAllowed( 'trackback' ) ) {
				$delurl = $this->mTitle->getFullURL( "action=deletetrackback&tbid=" .
					$o->tb_id . "&token=" . urlencode( $this->getContext()->getUser()->editToken() ) );
				$rmvtxt = wfMsg( 'trackbackremove', htmlspecialchars( $delurl ) );
			}

			$tbtext .= "\n";
			$tbtext .= wfMsgNoTrans( strlen( $o->tb_ex ) ? 'trackbackexcerpt' : 'trackback',
					$o->tb_title,
					$o->tb_url,
					$o->tb_ex,
					$o->tb_name,
					$rmvtxt );
		}

		$wgOut->wrapWikiMsg( "<div id='mw_trackbacks'>\n$1\n</div>\n", array( 'trackbackbox', $tbtext ) );
	}

	/**
	 * Removes trackback record for current article from trackbacks table
	 * @deprecated since 1.19
	 */
	public function deletetrackback() {
		return Action::factory( 'deletetrackback', $this )->show();
	}

	/**
	 * Handle action=render
	 */

	public function render() {
		global $wgOut;

		$wgOut->setArticleBodyOnly( true );
		$this->view();
	}

	/**
	 * Handle action=purge
	 */
	public function purge() {
		return Action::factory( 'purge', $this )->show();
	}

	/**
	 * Perform the actions of a page purging
	 */
	public function doPurge() {
		global $wgUseSquid;

		if( !wfRunHooks( 'ArticlePurge', array( &$this ) ) ){
			return false;
		}

		// Invalidate the cache
		$this->mTitle->invalidateCache();
		$this->clear();

		if ( $wgUseSquid ) {
			// Commit the transaction before the purge is sent
			$dbw = wfGetDB( DB_MASTER );
			$dbw->commit();

			// Send purge
			$update = SquidUpdate::newSimplePurge( $this->mTitle );
			$update->doUpdate();
		}

		if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
			if ( $this->getID() == 0 ) {
				$text = false;
			} else {
				$text = $this->getRawText();
			}

			MessageCache::singleton()->replace( $this->mTitle->getDBkey(), $text );
		}
	}

	/**
	 * Mark this particular edit/page as patrolled
	 * @deprecated since 1.19
	 */
	public function markpatrolled() {
		Action::factory( 'markpatrolled', $this )->show();
	}

	/**
	 * User-interface handler for the "watch" action.
	 * Requires Request to pass a token as of 1.19.
	 * @deprecated since 1.18
	 */
	public function watch() {
		Action::factory( 'watch', $this )->show();
	}

	/**
	 * Add this page to $wgUser's watchlist
	 *
	 * This is safe to be called multiple times
	 *
	 * @return bool true on successful watch operation
	 * @deprecated since 1.18
	 */
	public function doWatch() {
		global $wgUser;
		return WatchAction::doWatch( $this->mTitle, $wgUser );
	}

	/**
	 * User interface handler for the "unwatch" action.
	 * Requires Request to pass a token as of 1.19.
	 * @deprecated since 1.18
	 */
	public function unwatch() {
		Action::factory( 'unwatch', $this )->show();
	}

	/**
	 * Stop watching a page
	 * @return bool true on successful unwatch
	 * @deprecated since 1.18
	 */
	public function doUnwatch() {
		global $wgUser;
		return WatchAction::doUnwatch( $this->mTitle, $wgUser );
	}

	/**
	 * action=protect handler
	 */
	public function protect() {
		$form = new ProtectionForm( $this );
		$form->execute();
	}

	/**
	 * action=unprotect handler (alias)
	 */
	public function unprotect() {
		$this->protect();
	}

	/**
	 * Info about this page
	 * Called for ?action=info when $wgAllowPageInfo is on.
	 */
	public function info() {
		Action::factory( 'info', $this )->show();
	}

	/**
	 * Insert a new empty page record for this article.
	 * This *must* be followed up by creating a revision
	 * and running $this->updateRevisionOn( ... );
	 * or else the record will be left in a funky state.
	 * Best if all done inside a transaction.
	 *
	 * @param $dbw DatabaseBase
	 * @return int The newly created page_id key, or false if the title already existed
	 * @private
	 */
	public function insertOn( $dbw ) {
		wfProfileIn( __METHOD__ );

		$page_id = $dbw->nextSequenceValue( 'page_page_id_seq' );
		$dbw->insert( 'page', array(
			'page_id'           => $page_id,
			'page_namespace'    => $this->mTitle->getNamespace(),
			'page_title'        => $this->mTitle->getDBkey(),
			'page_counter'      => 0,
			'page_restrictions' => '',
			'page_is_redirect'  => 0, # Will set this shortly...
			'page_is_new'       => 1,
			'page_random'       => wfRandom(),
			'page_touched'      => $dbw->timestamp(),
			'page_latest'       => 0, # Fill this in shortly...
			'page_len'          => 0, # Fill this in shortly...
		), __METHOD__, 'IGNORE' );

		$affected = $dbw->affectedRows();

		if ( $affected ) {
			$newid = $dbw->insertId();
			$this->mTitle->resetArticleID( $newid );
		}
		wfProfileOut( __METHOD__ );

		return $affected ? $newid : false;
	}

	/**
	 * Update the page record to point to a newly saved revision.
	 *
	 * @param $dbw DatabaseBase: object
	 * @param $revision Revision: For ID number, and text used to set
						length and redirect status fields
	 * @param $lastRevision Integer: if given, will not overwrite the page field
	 *                      when different from the currently set value.
	 *                      Giving 0 indicates the new page flag should be set
	 *                      on.
	 * @param $lastRevIsRedirect Boolean: if given, will optimize adding and
	 *                           removing rows in redirect table.
	 * @return bool true on success, false on failure
	 * @private
	 */
	public function updateRevisionOn( &$dbw, $revision, $lastRevision = null, $lastRevIsRedirect = null ) {
		wfProfileIn( __METHOD__ );

		$text = $revision->getText();
		$rt = Title::newFromRedirectRecurse( $text );

		$conditions = array( 'page_id' => $this->getId() );

		if ( !is_null( $lastRevision ) ) {
			# An extra check against threads stepping on each other
			$conditions['page_latest'] = $lastRevision;
		}

		$dbw->update( 'page',
			array( /* SET */
				'page_latest'      => $revision->getId(),
				'page_touched'     => $dbw->timestamp(),
				'page_is_new'      => ( $lastRevision === 0 ) ? 1 : 0,
				'page_is_redirect' => $rt !== null ? 1 : 0,
				'page_len'         => strlen( $text ),
			),
			$conditions,
			__METHOD__ );

		$result = $dbw->affectedRows() != 0;
		if ( $result ) {
			$this->updateRedirectOn( $dbw, $rt, $lastRevIsRedirect );
		}

		wfProfileOut( __METHOD__ );
		return $result;
	}

	/**
	 * Add row to the redirect table if this is a redirect, remove otherwise.
	 *
	 * @param $dbw DatabaseBase
	 * @param $redirectTitle Title object pointing to the redirect target,
	 *                       or NULL if this is not a redirect
	 * @param $lastRevIsRedirect If given, will optimize adding and
	 *                           removing rows in redirect table.
	 * @return bool true on success, false on failure
	 * @private
	 */
	public function updateRedirectOn( &$dbw, $redirectTitle, $lastRevIsRedirect = null ) {
		// Always update redirects (target link might have changed)
		// Update/Insert if we don't know if the last revision was a redirect or not
		// Delete if changing from redirect to non-redirect
		$isRedirect = !is_null( $redirectTitle );

		if ( !$isRedirect && !is_null( $lastRevIsRedirect ) && $lastRevIsRedirect === $isRedirect ) {
			return true;
		}

		wfProfileIn( __METHOD__ );
		if ( $isRedirect ) {
			$this->insertRedirectEntry( $redirectTitle );
		} else {
			// This is not a redirect, remove row from redirect table
			$where = array( 'rd_from' => $this->getId() );
			$dbw->delete( 'redirect', $where, __METHOD__ );
		}

		if ( $this->getTitle()->getNamespace() == NS_FILE ) {
			RepoGroup::singleton()->getLocalRepo()->invalidateImageRedirect( $this->getTitle() );
		}
		wfProfileOut( __METHOD__ );

		return ( $dbw->affectedRows() != 0 );
	}

	/**
	 * If the given revision is newer than the currently set page_latest,
	 * update the page record. Otherwise, do nothing.
	 *
	 * @param $dbw Database object
	 * @param $revision Revision object
	 * @return mixed
	 */
	public function updateIfNewerOn( &$dbw, $revision ) {
		wfProfileIn( __METHOD__ );

		$row = $dbw->selectRow(
			array( 'revision', 'page' ),
			array( 'rev_id', 'rev_timestamp', 'page_is_redirect' ),
			array(
				'page_id' => $this->getId(),
				'page_latest=rev_id' ),
			__METHOD__ );

		if ( $row ) {
			if ( wfTimestamp( TS_MW, $row->rev_timestamp ) >= $revision->getTimestamp() ) {
				wfProfileOut( __METHOD__ );
				return false;
			}
			$prev = $row->rev_id;
			$lastRevIsRedirect = (bool)$row->page_is_redirect;
		} else {
			# No or missing previous revision; mark the page as new
			$prev = 0;
			$lastRevIsRedirect = null;
		}

		$ret = $this->updateRevisionOn( $dbw, $revision, $prev, $lastRevIsRedirect );

		wfProfileOut( __METHOD__ );
		return $ret;
	}

	/**
	 * @param $section empty/null/false or a section number (0, 1, 2, T1, T2...)
	 * @param $text String: new text of the section
	 * @param $summary String: new section's subject, only if $section is 'new'
	 * @param $edittime String: revision timestamp or null to use the current revision
	 * @return string Complete article text, or null if error
	 */
	public function replaceSection( $section, $text, $summary = '', $edittime = null ) {
		wfProfileIn( __METHOD__ );

		if ( strval( $section ) == '' ) {
			// Whole-page edit; let the whole text through
		} else {
			if ( is_null( $edittime ) ) {
				$rev = Revision::newFromTitle( $this->mTitle );
			} else {
				$dbw = wfGetDB( DB_MASTER );
				$rev = Revision::loadFromTimestamp( $dbw, $this->mTitle, $edittime );
			}

			if ( !$rev ) {
				wfDebug( "Article::replaceSection asked for bogus section (page: " .
					$this->getId() . "; section: $section; edittime: $edittime)\n" );
				wfProfileOut( __METHOD__ );
				return null;
			}

			$oldtext = $rev->getText();

			if ( $section == 'new' ) {
				# Inserting a new section
				$subject = $summary ? wfMsgForContent( 'newsectionheaderdefaultlevel', $summary ) . "\n\n" : '';
				$text = strlen( trim( $oldtext ) ) > 0
						? "{$oldtext}\n\n{$subject}{$text}"
						: "{$subject}{$text}";
			} else {
				# Replacing an existing section; roll out the big guns
				global $wgParser;

				$text = $wgParser->replaceSection( $oldtext, $section, $text );
			}
		}

		wfProfileOut( __METHOD__ );
		return $text;
	}

	/**
	 * Check flags and add EDIT_NEW or EDIT_UPDATE to them as needed.
	 * @param $flags Int
	 * @return Int updated $flags
	 */
	function checkFlags( $flags ) {
		if ( !( $flags & EDIT_NEW ) && !( $flags & EDIT_UPDATE ) ) {
			if ( $this->mTitle->getArticleID() ) {
				$flags |= EDIT_UPDATE;
			} else {
				$flags |= EDIT_NEW;
			}
		}

		return $flags;
	}

	/**
	 * Article::doEdit()
	 *
	 * Change an existing article or create a new article. Updates RC and all necessary caches,
	 * optionally via the deferred update array.
	 *
	 * $wgUser must be set before calling this function.
	 *
	 * @param $text String: new text
	 * @param $summary String: edit summary
	 * @param $flags Integer bitfield:
	 *      EDIT_NEW
	 *          Article is known or assumed to be non-existent, create a new one
	 *      EDIT_UPDATE
	 *          Article is known or assumed to be pre-existing, update it
	 *      EDIT_MINOR
	 *          Mark this edit minor, if the user is allowed to do so
	 *      EDIT_SUPPRESS_RC
	 *          Do not log the change in recentchanges
	 *      EDIT_FORCE_BOT
	 *          Mark the edit a "bot" edit regardless of user rights
	 *      EDIT_DEFER_UPDATES
	 *          Defer some of the updates until the end of index.php
	 *      EDIT_AUTOSUMMARY
	 *          Fill in blank summaries with generated text where possible
	 *
	 * If neither EDIT_NEW nor EDIT_UPDATE is specified, the status of the article will be detected.
	 * If EDIT_UPDATE is specified and the article doesn't exist, the function will an
	 * edit-gone-missing error. If EDIT_NEW is specified and the article does exist, an
	 * edit-already-exists error will be returned. These two conditions are also possible with
	 * auto-detection due to MediaWiki's performance-optimised locking strategy.
	 *
	 * @param $baseRevId the revision ID this edit was based off, if any
	 * @param $user User (optional), $wgUser will be used if not passed
	 *
	 * @return Status object. Possible errors:
	 *     edit-hook-aborted:       The ArticleSave hook aborted the edit but didn't set the fatal flag of $status
	 *     edit-gone-missing:       In update mode, but the article didn't exist
	 *     edit-conflict:           In update mode, the article changed unexpectedly
	 *     edit-no-change:          Warning that the text was the same as before
	 *     edit-already-exists:     In creation mode, but the article already exists
	 *
	 *  Extensions may define additional errors.
	 *
	 *  $return->value will contain an associative array with members as follows:
	 *     new:                     Boolean indicating if the function attempted to create a new article
	 *     revision:                The revision object for the inserted revision, or null
	 *
	 *  Compatibility note: this function previously returned a boolean value indicating success/failure
	 */
	public function doEdit( $text, $summary, $flags = 0, $baseRevId = false, $user = null ) {
		global $wgUser, $wgDBtransactions, $wgUseAutomaticEditSummaries;

		# Low-level sanity check
		if ( $this->mTitle->getText() === '' ) {
			throw new MWException( 'Something is trying to edit an article with an empty title' );
		}

		wfProfileIn( __METHOD__ );

		$user = is_null( $user ) ? $wgUser : $user;
		$status = Status::newGood( array() );

		# Load $this->mTitle->getArticleID() and $this->mLatest if it's not already
		$this->loadPageData();

		$flags = $this->checkFlags( $flags );

		if ( !wfRunHooks( 'ArticleSave', array( &$this, &$user, &$text, &$summary,
			$flags & EDIT_MINOR, null, null, &$flags, &$status ) ) )
		{
			wfDebug( __METHOD__ . ": ArticleSave hook aborted save!\n" );

			if ( $status->isOK() ) {
				$status->fatal( 'edit-hook-aborted' );
			}

			wfProfileOut( __METHOD__ );
			return $status;
		}

		# Silently ignore EDIT_MINOR if not allowed
		$isminor = ( $flags & EDIT_MINOR ) && $user->isAllowed( 'minoredit' );
		$bot = $flags & EDIT_FORCE_BOT;

		$oldtext = $this->getRawText(); // current revision
		$oldsize = strlen( $oldtext );

		# Provide autosummaries if one is not provided and autosummaries are enabled.
		if ( $wgUseAutomaticEditSummaries && $flags & EDIT_AUTOSUMMARY && $summary == '' ) {
			$summary = $this->getAutosummary( $oldtext, $text, $flags );
		}

		$editInfo = $this->prepareTextForEdit( $text, null, $user );
		$text = $editInfo->pst;
		$newsize = strlen( $text );

		$dbw = wfGetDB( DB_MASTER );
		$now = wfTimestampNow();
		$this->mTimestamp = $now;

		if ( $flags & EDIT_UPDATE ) {
			# Update article, but only if changed.
			$status->value['new'] = false;

			# Make sure the revision is either completely inserted or not inserted at all
			if ( !$wgDBtransactions ) {
				$userAbort = ignore_user_abort( true );
			}

			$changed = ( strcmp( $text, $oldtext ) != 0 );

			if ( $changed ) {
				if ( !$this->mLatest ) {
					# Article gone missing
					wfDebug( __METHOD__ . ": EDIT_UPDATE specified but article doesn't exist\n" );
					$status->fatal( 'edit-gone-missing' );

					wfProfileOut( __METHOD__ );
					return $status;
				}

				$revision = new Revision( array(
					'page'       => $this->getId(),
					'comment'    => $summary,
					'minor_edit' => $isminor,
					'text'       => $text,
					'parent_id'  => $this->mLatest,
					'user'       => $user->getId(),
					'user_text'  => $user->getName(),
					'timestamp'  => $now
				) );

				$dbw->begin();
				$revisionId = $revision->insertOn( $dbw );

				# Update page
				#
				# Note that we use $this->mLatest instead of fetching a value from the master DB
				# during the course of this function. This makes sure that EditPage can detect
				# edit conflicts reliably, either by $ok here, or by $article->getTimestamp()
				# before this function is called. A previous function used a separate query, this
				# creates a window where concurrent edits can cause an ignored edit conflict.
				$ok = $this->updateRevisionOn( $dbw, $revision, $this->mLatest );

				if ( !$ok ) {
					/* Belated edit conflict! Run away!! */
					$status->fatal( 'edit-conflict' );

					# Delete the invalid revision if the DB is not transactional
					if ( !$wgDBtransactions ) {
						$dbw->delete( 'revision', array( 'rev_id' => $revisionId ), __METHOD__ );
					}

					$revisionId = 0;
					$dbw->rollback();
				} else {
					global $wgUseRCPatrol;
					wfRunHooks( 'NewRevisionFromEditComplete', array( $this, $revision, $baseRevId, $user ) );
					# Update recentchanges
					if ( !( $flags & EDIT_SUPPRESS_RC ) ) {
						# Mark as patrolled if the user can do so
						$patrolled = $wgUseRCPatrol && !count(
							$this->mTitle->getUserPermissionsErrors( 'autopatrol', $user ) );
						# Add RC row to the DB
						$rc = RecentChange::notifyEdit( $now, $this->mTitle, $isminor, $user, $summary,
							$this->mLatest, $this->getTimestamp(), $bot, '', $oldsize, $newsize,
							$revisionId, $patrolled
						);

						# Log auto-patrolled edits
						if ( $patrolled ) {
							PatrolLog::record( $rc, true );
						}
					}
					$user->incEditCount();
					$dbw->commit();
				}
			} else {
				$status->warning( 'edit-no-change' );
				$revision = null;
				// Keep the same revision ID, but do some updates on it
				$revisionId = $this->getLatest();
				// Update page_touched, this is usually implicit in the page update
				// Other cache updates are done in onArticleEdit()
				$this->mTitle->invalidateCache();
			}

			if ( !$wgDBtransactions ) {
				ignore_user_abort( $userAbort );
			}

			// Now that ignore_user_abort is restored, we can respond to fatal errors
			if ( !$status->isOK() ) {
				wfProfileOut( __METHOD__ );
				return $status;
			}

			# Invalidate cache of this article and all pages using this article
			# as a template. Partly deferred.
			Article::onArticleEdit( $this->mTitle );
			# Update links tables, site stats, etc.
			$this->editUpdates( $text, $summary, $isminor, $now, $revisionId, $changed, $user );
		} else {
			# Create new article
			$status->value['new'] = true;

			$dbw->begin();

			# Add the page record; stake our claim on this title!
			# This will return false if the article already exists
			$newid = $this->insertOn( $dbw );

			if ( $newid === false ) {
				$dbw->rollback();
				$status->fatal( 'edit-already-exists' );

				wfProfileOut( __METHOD__ );
				return $status;
			}

			# Save the revision text...
			$revision = new Revision( array(
				'page'       => $newid,
				'comment'    => $summary,
				'minor_edit' => $isminor,
				'text'       => $text,
				'user'       => $user->getId(),
				'user_text'  => $user->getName(),
				'timestamp'  => $now
			) );
			$revisionId = $revision->insertOn( $dbw );

			$this->mTitle->resetArticleID( $newid );
			# Update the LinkCache. Resetting the Title ArticleID means it will rely on having that already cached
			# @todo FIXME?
			LinkCache::singleton()->addGoodLinkObj( $newid, $this->mTitle, strlen( $text ), (bool)Title::newFromRedirect( $text ), $revisionId );

			# Update the page record with revision data
			$this->updateRevisionOn( $dbw, $revision, 0 );

			wfRunHooks( 'NewRevisionFromEditComplete', array( $this, $revision, false, $user ) );

			# Update recentchanges
			if ( !( $flags & EDIT_SUPPRESS_RC ) ) {
				global $wgUseRCPatrol, $wgUseNPPatrol;

				# Mark as patrolled if the user can do so
				$patrolled = ( $wgUseRCPatrol || $wgUseNPPatrol ) && !count(
					$this->mTitle->getUserPermissionsErrors( 'autopatrol', $user ) );
				# Add RC row to the DB
				$rc = RecentChange::notifyNew( $now, $this->mTitle, $isminor, $user, $summary, $bot,
					'', strlen( $text ), $revisionId, $patrolled );

				# Log auto-patrolled edits
				if ( $patrolled ) {
					PatrolLog::record( $rc, true );
				}
			}
			$user->incEditCount();
			$dbw->commit();

			# Update links, etc.
			$this->editUpdates( $text, $summary, $isminor, $now, $revisionId, true, $user, true );

			# Clear caches
			Article::onArticleCreate( $this->mTitle );

			wfRunHooks( 'ArticleInsertComplete', array( &$this, &$user, $text, $summary,
				$flags & EDIT_MINOR, null, null, &$flags, $revision ) );
		}

		# Do updates right now unless deferral was requested
		if ( !( $flags & EDIT_DEFER_UPDATES ) ) {
			wfDoUpdates();
		}

		// Return the new revision (or null) to the caller
		$status->value['revision'] = $revision;

		wfRunHooks( 'ArticleSaveComplete', array( &$this, &$user, $text, $summary,
			$flags & EDIT_MINOR, null, null, &$flags, $revision, &$status, $baseRevId ) );

		# Promote user to any groups they meet the criteria for
		$user->addAutopromoteOnceGroups( 'onEdit' );

		wfProfileOut( __METHOD__ );
		return $status;
	}

	/**
	 * Output a redirect back to the article.
	 * This is typically used after an edit.
	 *
	 * @deprecated in 1.19; call $wgOut->redirect() directly
	 * @param $noRedir Boolean: add redirect=no
	 * @param $sectionAnchor String: section to redirect to, including "#"
	 * @param $extraQuery String: extra query params
	 */
	public function doRedirect( $noRedir = false, $sectionAnchor = '', $extraQuery = '' ) {
		wfDeprecated( __METHOD__ );
		global $wgOut;

		if ( $noRedir ) {
			$query = 'redirect=no';
			if ( $extraQuery )
				$query .= "&$extraQuery";
		} else {
			$query = $extraQuery;
		}

		$wgOut->redirect( $this->mTitle->getFullURL( $query ) . $sectionAnchor );
	}

	/**
	 * Update the article's restriction field, and leave a log entry.
	 *
	 * @param $limit Array: set of restriction keys
	 * @param $reason String
	 * @param &$cascade Integer. Set to false if cascading protection isn't allowed.
	 * @param $expiry Array: per restriction type expiration
	 * @return bool true on success
	 */
	public function updateRestrictions( $limit = array(), $reason = '', &$cascade = 0, $expiry = array() ) {
		global $wgUser, $wgContLang;

		$restrictionTypes = $this->mTitle->getRestrictionTypes();

		$id = $this->mTitle->getArticleID();

		if ( $id <= 0 ) {
			wfDebug( "updateRestrictions failed: article id $id <= 0\n" );
			return false;
		}

		if ( wfReadOnly() ) {
			wfDebug( "updateRestrictions failed: read-only\n" );
			return false;
		}

		if ( !$this->mTitle->userCan( 'protect' ) ) {
			wfDebug( "updateRestrictions failed: insufficient permissions\n" );
			return false;
		}

		if ( !$cascade ) {
			$cascade = false;
		}

		// Take this opportunity to purge out expired restrictions
		Title::purgeExpiredRestrictions();

		# @todo FIXME: Same limitations as described in ProtectionForm.php (line 37);
		# we expect a single selection, but the schema allows otherwise.
		$current = array();
		$updated = Article::flattenRestrictions( $limit );
		$changed = false;

		foreach ( $restrictionTypes as $action ) {
			if ( isset( $expiry[$action] ) ) {
				# Get current restrictions on $action
				$aLimits = $this->mTitle->getRestrictions( $action );
				$current[$action] = implode( '', $aLimits );
				# Are any actual restrictions being dealt with here?
				$aRChanged = count( $aLimits ) || !empty( $limit[$action] );

				# If something changed, we need to log it. Checking $aRChanged
				# assures that "unprotecting" a page that is not protected does
				# not log just because the expiry was "changed".
				if ( $aRChanged && $this->mTitle->mRestrictionsExpiry[$action] != $expiry[$action] ) {
					$changed = true;
				}
			}
		}

		$current = Article::flattenRestrictions( $current );

		$changed = ( $changed || $current != $updated );
		$changed = $changed || ( $updated && $this->mTitle->areRestrictionsCascading() != $cascade );
		$protect = ( $updated != '' );

		# If nothing's changed, do nothing
		if ( $changed ) {
			if ( wfRunHooks( 'ArticleProtect', array( &$this, &$wgUser, $limit, $reason ) ) ) {
				$dbw = wfGetDB( DB_MASTER );

				# Prepare a null revision to be added to the history
				$modified = $current != '' && $protect;

				if ( $protect ) {
					$comment_type = $modified ? 'modifiedarticleprotection' : 'protectedarticle';
				} else {
					$comment_type = 'unprotectedarticle';
				}

				$comment = $wgContLang->ucfirst( wfMsgForContent( $comment_type, $this->mTitle->getPrefixedText() ) );

				# Only restrictions with the 'protect' right can cascade...
				# Otherwise, people who cannot normally protect can "protect" pages via transclusion
				$editrestriction = isset( $limit['edit'] ) ? array( $limit['edit'] ) : $this->mTitle->getRestrictions( 'edit' );

				# The schema allows multiple restrictions
				if ( !in_array( 'protect', $editrestriction ) && !in_array( 'sysop', $editrestriction ) ) {
					$cascade = false;
				}

				$cascade_description = '';

				if ( $cascade ) {
					$cascade_description = ' [' . wfMsgForContent( 'protect-summary-cascade' ) . ']';
				}

				if ( $reason ) {
					$comment .= ": $reason";
				}

				$editComment = $comment;
				$encodedExpiry = array();
				$protect_description = '';
				foreach ( $limit as $action => $restrictions ) {
					if ( !isset( $expiry[$action] ) )
						$expiry[$action] = $dbw->getInfinity();

					$encodedExpiry[$action] = $dbw->encodeExpiry( $expiry[$action] );
					if ( $restrictions != '' ) {
						$protect_description .= "[$action=$restrictions] (";
						if ( $encodedExpiry[$action] != 'infinity' ) {
							$protect_description .= wfMsgForContent( 'protect-expiring',
								$wgContLang->timeanddate( $expiry[$action], false, false ) ,
								$wgContLang->date( $expiry[$action], false, false ) ,
								$wgContLang->time( $expiry[$action], false, false ) );
						} else {
							$protect_description .= wfMsgForContent( 'protect-expiry-indefinite' );
						}

						$protect_description .= ') ';
					}
				}
				$protect_description = trim( $protect_description );

				if ( $protect_description && $protect ) {
					$editComment .= " ($protect_description)";
				}

				if ( $cascade ) {
					$editComment .= "$cascade_description";
				}

				# Update restrictions table
				foreach ( $limit as $action => $restrictions ) {
					if ( $restrictions != '' ) {
						$dbw->replace( 'page_restrictions', array( array( 'pr_page', 'pr_type' ) ),
							array( 'pr_page' => $id,
								'pr_type' => $action,
								'pr_level' => $restrictions,
								'pr_cascade' => ( $cascade && $action == 'edit' ) ? 1 : 0,
								'pr_expiry' => $encodedExpiry[$action]
							),
							__METHOD__
						);
					} else {
						$dbw->delete( 'page_restrictions', array( 'pr_page' => $id,
							'pr_type' => $action ), __METHOD__ );
					}
				}

				# Insert a null revision
				$nullRevision = Revision::newNullRevision( $dbw, $id, $editComment, true );
				$nullRevId = $nullRevision->insertOn( $dbw );

				$latest = $this->getLatest();
				# Update page record
				$dbw->update( 'page',
					array( /* SET */
						'page_touched' => $dbw->timestamp(),
						'page_restrictions' => '',
						'page_latest' => $nullRevId
					), array( /* WHERE */
						'page_id' => $id
					), 'Article::protect'
				);

				wfRunHooks( 'NewRevisionFromEditComplete', array( $this, $nullRevision, $latest, $wgUser ) );
				wfRunHooks( 'ArticleProtectComplete', array( &$this, &$wgUser, $limit, $reason ) );

				# Update the protection log
				$log = new LogPage( 'protect' );
				if ( $protect ) {
					$params = array( $protect_description, $cascade ? 'cascade' : '' );
					$log->addEntry( $modified ? 'modify' : 'protect', $this->mTitle, trim( $reason ), $params );
				} else {
					$log->addEntry( 'unprotect', $this->mTitle, $reason );
				}
			} # End hook
		} # End "changed" check

		return true;
	}

	/**
	 * Take an array of page restrictions and flatten it to a string
	 * suitable for insertion into the page_restrictions field.
	 * @param $limit Array
	 * @return String
	 */
	protected static function flattenRestrictions( $limit ) {
		if ( !is_array( $limit ) ) {
			throw new MWException( 'Article::flattenRestrictions given non-array restriction set' );
		}

		$bits = array();
		ksort( $limit );

		foreach ( $limit as $action => $restrictions ) {
			if ( $restrictions != '' ) {
				$bits[] = "$action=$restrictions";
			}
		}

		return implode( ':', $bits );
	}

	/**
	 * Auto-generates a deletion reason
	 *
	 * @param &$hasHistory Boolean: whether the page has a history
	 * @return mixed String containing deletion reason or empty string, or boolean false
	 *    if no revision occurred
	 */
	public function generateReason( &$hasHistory ) {
		global $wgContLang;

		$dbw = wfGetDB( DB_MASTER );
		// Get the last revision
		$rev = Revision::newFromTitle( $this->mTitle );

		if ( is_null( $rev ) ) {
			return false;
		}

		// Get the article's contents
		$contents = $rev->getText();
		$blank = false;

		// If the page is blank, use the text from the previous revision,
		// which can only be blank if there's a move/import/protect dummy revision involved
		if ( $contents == '' ) {
			$prev = $rev->getPrevious();

			if ( $prev )	{
				$contents = $prev->getText();
				$blank = true;
			}
		}

		// Find out if there was only one contributor
		// Only scan the last 20 revisions
		$res = $dbw->select( 'revision', 'rev_user_text',
			array( 'rev_page' => $this->getID(), $dbw->bitAnd( 'rev_deleted', Revision::DELETED_USER ) . ' = 0' ),
			__METHOD__,
			array( 'LIMIT' => 20 )
		);

		if ( $res === false ) {
			// This page has no revisions, which is very weird
			return false;
		}

		$hasHistory = ( $res->numRows() > 1 );
		$row = $dbw->fetchObject( $res );

		if ( $row ) { // $row is false if the only contributor is hidden
			$onlyAuthor = $row->rev_user_text;
			// Try to find a second contributor
			foreach ( $res as $row ) {
				if ( $row->rev_user_text != $onlyAuthor ) { // Bug 22999
					$onlyAuthor = false;
					break;
				}
			}
		} else {
			$onlyAuthor = false;
		}

		// Generate the summary with a '$1' placeholder
		if ( $blank ) {
			// The current revision is blank and the one before is also
			// blank. It's just not our lucky day
			$reason = wfMsgForContent( 'exbeforeblank', '$1' );
		} else {
			if ( $onlyAuthor ) {
				$reason = wfMsgForContent( 'excontentauthor', '$1', $onlyAuthor );
			} else {
				$reason = wfMsgForContent( 'excontent', '$1' );
			}
		}

		if ( $reason == '-' ) {
			// Allow these UI messages to be blanked out cleanly
			return '';
		}

		// Replace newlines with spaces to prevent uglyness
		$contents = preg_replace( "/[\n\r]/", ' ', $contents );
		// Calculate the maximum amount of chars to get
		// Max content length = max comment length - length of the comment (excl. $1)
		$maxLength = 255 - ( strlen( $reason ) - 2 );
		$contents = $wgContLang->truncate( $contents, $maxLength );
		// Remove possible unfinished links
		$contents = preg_replace( '/\[\[([^\]]*)\]?$/', '$1', $contents );
		// Now replace the '$1' placeholder
		$reason = str_replace( '$1', $contents, $reason );

		return $reason;
	}


	/**
	 * UI entry point for page deletion
	 */
	public function delete() {
		global $wgOut, $wgRequest;

		$confirm = $wgRequest->wasPosted() &&
				$this->getContext()->getUser()->matchEditToken( $wgRequest->getVal( 'wpEditToken' ) );

		$this->DeleteReasonList = $wgRequest->getText( 'wpDeleteReasonList', 'other' );
		$this->DeleteReason = $wgRequest->getText( 'wpReason' );

		$reason = $this->DeleteReasonList;

		if ( $reason != 'other' && $this->DeleteReason != '' ) {
			// Entry from drop down menu + additional comment
			$reason .= wfMsgForContent( 'colon-separator' ) . $this->DeleteReason;
		} elseif ( $reason == 'other' ) {
			$reason = $this->DeleteReason;
		}

		# Flag to hide all contents of the archived revisions
		$suppress = $wgRequest->getVal( 'wpSuppress' ) && $this->getContext()->getUser()->isAllowed( 'suppressrevision' );

		# This code desperately needs to be totally rewritten

		# Read-only check...
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();

			return;
		}

		# Check permissions
		$permission_errors = $this->mTitle->getUserPermissionsErrors( 'delete', $this->getContext()->getUser() );

		if ( count( $permission_errors ) > 0 ) {
			$wgOut->showPermissionsErrorPage( $permission_errors );

			return;
		}

		$wgOut->setPagetitle( wfMsg( 'delete-confirm', $this->mTitle->getPrefixedText() ) );

		# Better double-check that it hasn't been deleted yet!
		$dbw = wfGetDB( DB_MASTER );
		$conds = $this->mTitle->pageCond();
		$latest = $dbw->selectField( 'page', 'page_latest', $conds, __METHOD__ );
		if ( $latest === false ) {
			$wgOut->showFatalError(
				Html::rawElement(
					'div',
					array( 'class' => 'error mw-error-cannotdelete' ),
					wfMsgExt( 'cannotdelete', array( 'parse' ),
						wfEscapeWikiText( $this->mTitle->getPrefixedText() ) )
				)
			);
			$wgOut->addHTML( Xml::element( 'h2', null, LogPage::logName( 'delete' ) ) );
			LogEventsList::showLogExtract(
				$wgOut,
				'delete',
				$this->mTitle->getPrefixedText()
			);

			return;
		}

		# Hack for big sites
		$bigHistory = $this->isBigDeletion();
		if ( $bigHistory && !$this->mTitle->userCan( 'bigdelete' ) ) {
			global $wgLang, $wgDeleteRevisionsLimit;

			$wgOut->wrapWikiMsg( "<div class='error'>\n$1\n</div>\n",
				array( 'delete-toobig', $wgLang->formatNum( $wgDeleteRevisionsLimit ) ) );

			return;
		}

		if ( $confirm ) {
			$this->doDelete( $reason, $suppress );

			if ( $wgRequest->getCheck( 'wpWatch' ) && $this->getContext()->getUser()->isLoggedIn() ) {
				$this->doWatch();
			} elseif ( $this->mTitle->userIsWatching() ) {
				$this->doUnwatch();
			}

			return;
		}

		// Generate deletion reason
		$hasHistory = false;
		if ( !$reason ) {
			$reason = $this->generateReason( $hasHistory );
		}

		// If the page has a history, insert a warning
		if ( $hasHistory && !$confirm ) {
			global $wgLang;

			$revisions = $this->estimateRevisionCount();
			// @todo FIXME: i18n issue/patchwork message
			$wgOut->addHTML( '<strong class="mw-delete-warning-revisions">' .
				wfMsgExt( 'historywarning', array( 'parseinline' ), $wgLang->formatNum( $revisions ) ) .
				wfMsgHtml( 'word-separator' ) . Linker::link( $this->mTitle,
					wfMsgHtml( 'history' ),
					array( 'rel' => 'archives' ),
					array( 'action' => 'history' ) ) .
				'</strong>'
			);

			if ( $bigHistory ) {
				global $wgDeleteRevisionsLimit;
				$wgOut->wrapWikiMsg( "<div class='error'>\n$1\n</div>\n",
					array( 'delete-warning-toobig', $wgLang->formatNum( $wgDeleteRevisionsLimit ) ) );
			}
		}

		return $this->confirmDelete( $reason );
	}

	/**
	 * @return bool whether or not the page surpasses $wgDeleteRevisionsLimit revisions
	 */
	public function isBigDeletion() {
		global $wgDeleteRevisionsLimit;

		if ( $wgDeleteRevisionsLimit ) {
			$revCount = $this->estimateRevisionCount();

			return $revCount > $wgDeleteRevisionsLimit;
		}

		return false;
	}

	/**
	 * @return int approximate revision count
	 */
	public function estimateRevisionCount() {
		$dbr = wfGetDB( DB_SLAVE );

		// For an exact count...
		// return $dbr->selectField( 'revision', 'COUNT(*)',
		//	array( 'rev_page' => $this->getId() ), __METHOD__ );
		return $dbr->estimateRowCount( 'revision', '*',
			array( 'rev_page' => $this->getId() ), __METHOD__ );
	}

	/**
	 * Get the last N authors
	 * @param $num Integer: number of revisions to get
	 * @param $revLatest String: the latest rev_id, selected from the master (optional)
	 * @return array Array of authors, duplicates not removed
	 */
	public function getLastNAuthors( $num, $revLatest = 0 ) {
		wfProfileIn( __METHOD__ );
		// First try the slave
		// If that doesn't have the latest revision, try the master
		$continue = 2;
		$db = wfGetDB( DB_SLAVE );

		do {
			$res = $db->select( array( 'page', 'revision' ),
				array( 'rev_id', 'rev_user_text' ),
				array(
					'page_namespace' => $this->mTitle->getNamespace(),
					'page_title' => $this->mTitle->getDBkey(),
					'rev_page = page_id'
				), __METHOD__,
				array(
					'ORDER BY' => 'rev_timestamp DESC',
					'LIMIT' => $num
				)
			);

			if ( !$res ) {
				wfProfileOut( __METHOD__ );
				return array();
			}

			$row = $db->fetchObject( $res );

			if ( $continue == 2 && $revLatest && $row->rev_id != $revLatest ) {
				$db = wfGetDB( DB_MASTER );
				$continue--;
			} else {
				$continue = 0;
			}
		} while ( $continue );

		$authors = array( $row->rev_user_text );

		foreach ( $res as $row ) {
			$authors[] = $row->rev_user_text;
		}

		wfProfileOut( __METHOD__ );
		return $authors;
	}

	/**
	 * Output deletion confirmation dialog
	 * @todo FIXME: Move to another file?
	 * @param $reason String: prefilled reason
	 */
	public function confirmDelete( $reason ) {
		global $wgOut;

		wfDebug( "Article::confirmDelete\n" );

		$deleteBackLink = Linker::linkKnown( $this->mTitle );
		$wgOut->setSubtitle( wfMsgHtml( 'delete-backlink', $deleteBackLink ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->addWikiMsg( 'confirmdeletetext' );

		wfRunHooks( 'ArticleConfirmDelete', array( $this, $wgOut, &$reason ) );

		if ( $this->getContext()->getUser()->isAllowed( 'suppressrevision' ) ) {
			$suppress = "<tr id=\"wpDeleteSuppressRow\">
					<td></td>
					<td class='mw-input'><strong>" .
						Xml::checkLabel( wfMsg( 'revdelete-suppress' ),
							'wpSuppress', 'wpSuppress', false, array( 'tabindex' => '4' ) ) .
					"</strong></td>
				</tr>";
		} else {
			$suppress = '';
		}
		$checkWatch = $this->getContext()->getUser()->getBoolOption( 'watchdeletion' ) || $this->mTitle->userIsWatching();

		$form = Xml::openElement( 'form', array( 'method' => 'post',
			'action' => $this->mTitle->getLocalURL( 'action=delete' ), 'id' => 'deleteconfirm' ) ) .
			Xml::openElement( 'fieldset', array( 'id' => 'mw-delete-table' ) ) .
			Xml::tags( 'legend', null, wfMsgExt( 'delete-legend', array( 'parsemag', 'escapenoentities' ) ) ) .
			Xml::openElement( 'table', array( 'id' => 'mw-deleteconfirm-table' ) ) .
			"<tr id=\"wpDeleteReasonListRow\">
				<td class='mw-label'>" .
					Xml::label( wfMsg( 'deletecomment' ), 'wpDeleteReasonList' ) .
				"</td>
				<td class='mw-input'>" .
					Xml::listDropDown( 'wpDeleteReasonList',
						wfMsgForContent( 'deletereason-dropdown' ),
						wfMsgForContent( 'deletereasonotherlist' ), '', 'wpReasonDropDown', 1 ) .
				"</td>
			</tr>
			<tr id=\"wpDeleteReasonRow\">
				<td class='mw-label'>" .
					Xml::label( wfMsg( 'deleteotherreason' ), 'wpReason' ) .
				"</td>
				<td class='mw-input'>" .
				Html::input( 'wpReason', $reason, 'text', array(
					'size' => '60',
					'maxlength' => '255',
					'tabindex' => '2',
					'id' => 'wpReason',
					'autofocus'
				) ) .
				"</td>
			</tr>";

		# Disallow watching if user is not logged in
		if ( $this->getContext()->getUser()->isLoggedIn() ) {
			$form .= "
			<tr>
				<td></td>
				<td class='mw-input'>" .
					Xml::checkLabel( wfMsg( 'watchthis' ),
						'wpWatch', 'wpWatch', $checkWatch, array( 'tabindex' => '3' ) ) .
				"</td>
			</tr>";
		}

		$form .= "
			$suppress
			<tr>
				<td></td>
				<td class='mw-submit'>" .
					Xml::submitButton( wfMsg( 'deletepage' ),
						array( 'name' => 'wpConfirmB', 'id' => 'wpConfirmB', 'tabindex' => '5' ) ) .
				"</td>
			</tr>" .
			Xml::closeElement( 'table' ) .
			Xml::closeElement( 'fieldset' ) .
			Html::hidden( 'wpEditToken', $this->getContext()->getUser()->editToken() ) .
			Xml::closeElement( 'form' );

			if ( $this->getContext()->getUser()->isAllowed( 'editinterface' ) ) {
				$title = Title::makeTitle( NS_MEDIAWIKI, 'Deletereason-dropdown' );
				$link = Linker::link(
					$title,
					wfMsgHtml( 'delete-edit-reasonlist' ),
					array(),
					array( 'action' => 'edit' )
				);
				$form .= '<p class="mw-delete-editreasons">' . $link . '</p>';
			}

		$wgOut->addHTML( $form );
		$wgOut->addHTML( Xml::element( 'h2', null, LogPage::logName( 'delete' ) ) );
		LogEventsList::showLogExtract( $wgOut, 'delete',
			$this->mTitle->getPrefixedText()
		);
	}

	/**
	 * Perform a deletion and output success or failure messages
	 */
	public function doDelete( $reason, $suppress = false ) {
		global $wgOut;

		$id = $this->mTitle->getArticleID( Title::GAID_FOR_UPDATE );

		$error = '';
		if ( $this->doDeleteArticle( $reason, $suppress, $id, $error ) ) {
			$deleted = $this->mTitle->getPrefixedText();

			$wgOut->setPagetitle( wfMsg( 'actioncomplete' ) );
			$wgOut->setRobotPolicy( 'noindex,nofollow' );

			$loglink = '[[Special:Log/delete|' . wfMsgNoTrans( 'deletionlog' ) . ']]';

			$wgOut->addWikiMsg( 'deletedtext', wfEscapeWikiText( $deleted ), $loglink );
			$wgOut->returnToMain( false );
		} else {
			if ( $error == '' ) {
				$wgOut->showFatalError(
					Html::rawElement(
						'div',
						array( 'class' => 'error mw-error-cannotdelete' ),
						wfMsgExt( 'cannotdelete', array( 'parse' ),
							wfEscapeWikiText( $this->mTitle->getPrefixedText() ) )
					)
				);

				$wgOut->addHTML( Xml::element( 'h2', null, LogPage::logName( 'delete' ) ) );

				LogEventsList::showLogExtract(
					$wgOut,
					'delete',
					$this->mTitle->getPrefixedText()
				);
			} else {
				$wgOut->showFatalError( $error );
			}
		}
	}

	/**
	 * Back-end article deletion
	 * Deletes the article with database consistency, writes logs, purges caches
	 *
	 * @param $reason string delete reason for deletion log
	 * @param suppress bitfield
	 * 	Revision::DELETED_TEXT
	 * 	Revision::DELETED_COMMENT
	 * 	Revision::DELETED_USER
	 * 	Revision::DELETED_RESTRICTED
	 * @param $id int article ID
	 * @param $commit boolean defaults to true, triggers transaction end
	 * @return boolean true if successful
	 */
	public function doDeleteArticle( $reason, $suppress = false, $id = 0, $commit = true, &$error = '' ) {
		global $wgDeferredUpdateList, $wgUseTrackbacks;
		global $wgUser;

		wfDebug( __METHOD__ . "\n" );

		if ( ! wfRunHooks( 'ArticleDelete', array( &$this, &$wgUser, &$reason, &$error ) ) ) {
			return false;
		}
		$dbw = wfGetDB( DB_MASTER );
		$t = $this->mTitle->getDBkey();
		$id = $id ? $id : $this->mTitle->getArticleID( Title::GAID_FOR_UPDATE );

		if ( $t === '' || $id == 0 ) {
			return false;
		}

		$u = new SiteStatsUpdate( 0, 1, - (int)$this->isCountable(), -1 );
		array_push( $wgDeferredUpdateList, $u );

		// Bitfields to further suppress the content
		if ( $suppress ) {
			$bitfield = 0;
			// This should be 15...
			$bitfield |= Revision::DELETED_TEXT;
			$bitfield |= Revision::DELETED_COMMENT;
			$bitfield |= Revision::DELETED_USER;
			$bitfield |= Revision::DELETED_RESTRICTED;
		} else {
			$bitfield = 'rev_deleted';
		}

		$dbw->begin();
		// For now, shunt the revision data into the archive table.
		// Text is *not* removed from the text table; bulk storage
		// is left intact to avoid breaking block-compression or
		// immutable storage schemes.
		//
		// For backwards compatibility, note that some older archive
		// table entries will have ar_text and ar_flags fields still.
		//
		// In the future, we may keep revisions and mark them with
		// the rev_deleted field, which is reserved for this purpose.
		$dbw->insertSelect( 'archive', array( 'page', 'revision' ),
			array(
				'ar_namespace'  => 'page_namespace',
				'ar_title'      => 'page_title',
				'ar_comment'    => 'rev_comment',
				'ar_user'       => 'rev_user',
				'ar_user_text'  => 'rev_user_text',
				'ar_timestamp'  => 'rev_timestamp',
				'ar_minor_edit' => 'rev_minor_edit',
				'ar_rev_id'     => 'rev_id',
				'ar_text_id'    => 'rev_text_id',
				'ar_text'       => '\'\'', // Be explicit to appease
				'ar_flags'      => '\'\'', // MySQL's "strict mode"...
				'ar_len'        => 'rev_len',
				'ar_page_id'    => 'page_id',
				'ar_deleted'    => $bitfield
			), array(
				'page_id' => $id,
				'page_id = rev_page'
			), __METHOD__
		);

		# Delete restrictions for it
		$dbw->delete( 'page_restrictions', array ( 'pr_page' => $id ), __METHOD__ );

		# Now that it's safely backed up, delete it
		$dbw->delete( 'page', array( 'page_id' => $id ), __METHOD__ );
		$ok = ( $dbw->affectedRows() > 0 ); // getArticleId() uses slave, could be laggy

		if ( !$ok ) {
			$dbw->rollback();
			return false;
		}

		# Fix category table counts
		$cats = array();
		$res = $dbw->select( 'categorylinks', 'cl_to', array( 'cl_from' => $id ), __METHOD__ );

		foreach ( $res as $row ) {
			$cats [] = $row->cl_to;
		}

		$this->updateCategoryCounts( array(), $cats );

		# If using cascading deletes, we can skip some explicit deletes
		if ( !$dbw->cascadingDeletes() ) {
			$dbw->delete( 'revision', array( 'rev_page' => $id ), __METHOD__ );

			if ( $wgUseTrackbacks )
				$dbw->delete( 'trackbacks', array( 'tb_page' => $id ), __METHOD__ );

			# Delete outgoing links
			$dbw->delete( 'pagelinks', array( 'pl_from' => $id ) );
			$dbw->delete( 'imagelinks', array( 'il_from' => $id ) );
			$dbw->delete( 'categorylinks', array( 'cl_from' => $id ) );
			$dbw->delete( 'templatelinks', array( 'tl_from' => $id ) );
			$dbw->delete( 'externallinks', array( 'el_from' => $id ) );
			$dbw->delete( 'langlinks', array( 'll_from' => $id ) );
			$dbw->delete( 'iwlinks', array( 'iwl_from' => $id ) );
			$dbw->delete( 'redirect', array( 'rd_from' => $id ) );
		}

		# If using cleanup triggers, we can skip some manual deletes
		if ( !$dbw->cleanupTriggers() ) {
			# Clean up recentchanges entries...
			$dbw->delete( 'recentchanges',
				array( 'rc_type != ' . RC_LOG,
					'rc_namespace' => $this->mTitle->getNamespace(),
					'rc_title' => $this->mTitle->getDBkey() ),
				__METHOD__ );
			$dbw->delete( 'recentchanges',
				array( 'rc_type != ' . RC_LOG, 'rc_cur_id' => $id ),
				__METHOD__ );
		}

		# Clear caches
		Article::onArticleDelete( $this->mTitle );

		# Clear the cached article id so the interface doesn't act like we exist
		$this->mTitle->resetArticleID( 0 );

		# Log the deletion, if the page was suppressed, log it at Oversight instead
		$logtype = $suppress ? 'suppress' : 'delete';
		$log = new LogPage( $logtype );

		# Make sure logging got through
		$log->addEntry( 'delete', $this->mTitle, $reason, array() );

		if ( $commit ) {
			$dbw->commit();
		}

		wfRunHooks( 'ArticleDeleteComplete', array( &$this, &$wgUser, $reason, $id ) );
		return true;
	}

	/**
	 * Roll back the most recent consecutive set of edits to a page
	 * from the same user; fails if there are no eligible edits to
	 * roll back to, e.g. user is the sole contributor. This function
	 * performs permissions checks on $wgUser, then calls commitRollback()
	 * to do the dirty work
	 *
	 * @param $fromP String: Name of the user whose edits to rollback.
	 * @param $summary String: Custom summary. Set to default summary if empty.
	 * @param $token String: Rollback token.
	 * @param $bot Boolean: If true, mark all reverted edits as bot.
	 *
	 * @param $resultDetails Array: contains result-specific array of additional values
	 *    'alreadyrolled' : 'current' (rev)
	 *    success        : 'summary' (str), 'current' (rev), 'target' (rev)
	 *
	 * @return array of errors, each error formatted as
	 *   array(messagekey, param1, param2, ...).
	 * On success, the array is empty.  This array can also be passed to
	 * OutputPage::showPermissionsErrorPage().
	 */
	public function doRollback( $fromP, $summary, $token, $bot, &$resultDetails ) {
		global $wgUser;

		$resultDetails = null;

		# Check permissions
		$editErrors = $this->mTitle->getUserPermissionsErrors( 'edit', $wgUser );
		$rollbackErrors = $this->mTitle->getUserPermissionsErrors( 'rollback', $wgUser );
		$errors = array_merge( $editErrors, wfArrayDiff2( $rollbackErrors, $editErrors ) );

		if ( !$wgUser->matchEditToken( $token, array( $this->mTitle->getPrefixedText(), $fromP ) ) ) {
			$errors[] = array( 'sessionfailure' );
		}

		if ( $wgUser->pingLimiter( 'rollback' ) || $wgUser->pingLimiter() ) {
			$errors[] = array( 'actionthrottledtext' );
		}

		# If there were errors, bail out now
		if ( !empty( $errors ) ) {
			return $errors;
		}

		return $this->commitRollback( $fromP, $summary, $bot, $resultDetails );
	}

	/**
	 * Backend implementation of doRollback(), please refer there for parameter
	 * and return value documentation
	 *
	 * NOTE: This function does NOT check ANY permissions, it just commits the
	 * rollback to the DB Therefore, you should only call this function direct-
	 * ly if you want to use custom permissions checks. If you don't, use
	 * doRollback() instead.
	 */
	public function commitRollback( $fromP, $summary, $bot, &$resultDetails ) {
		global $wgUseRCPatrol, $wgUser, $wgLang;

		$dbw = wfGetDB( DB_MASTER );

		if ( wfReadOnly() ) {
			return array( array( 'readonlytext' ) );
		}

		# Get the last editor
		$current = Revision::newFromTitle( $this->mTitle );
		if ( is_null( $current ) ) {
			# Something wrong... no page?
			return array( array( 'notanarticle' ) );
		}

		$from = str_replace( '_', ' ', $fromP );
		# User name given should match up with the top revision.
		# If the user was deleted then $from should be empty.
		if ( $from != $current->getUserText() ) {
			$resultDetails = array( 'current' => $current );
			return array( array( 'alreadyrolled',
				htmlspecialchars( $this->mTitle->getPrefixedText() ),
				htmlspecialchars( $fromP ),
				htmlspecialchars( $current->getUserText() )
			) );
		}

		# Get the last edit not by this guy...
		# Note: these may not be public values
		$user = intval( $current->getRawUser() );
		$user_text = $dbw->addQuotes( $current->getRawUserText() );
		$s = $dbw->selectRow( 'revision',
			array( 'rev_id', 'rev_timestamp', 'rev_deleted' ),
			array( 'rev_page' => $current->getPage(),
				"rev_user != {$user} OR rev_user_text != {$user_text}"
			), __METHOD__,
			array( 'USE INDEX' => 'page_timestamp',
				'ORDER BY' => 'rev_timestamp DESC' )
			);
		if ( $s === false ) {
			# No one else ever edited this page
			return array( array( 'cantrollback' ) );
		} elseif ( $s->rev_deleted & Revision::DELETED_TEXT || $s->rev_deleted & Revision::DELETED_USER ) {
			# Only admins can see this text
			return array( array( 'notvisiblerev' ) );
		}

		$set = array();
		if ( $bot && $wgUser->isAllowed( 'markbotedits' ) ) {
			# Mark all reverted edits as bot
			$set['rc_bot'] = 1;
		}

		if ( $wgUseRCPatrol ) {
			# Mark all reverted edits as patrolled
			$set['rc_patrolled'] = 1;
		}

		if ( count( $set ) ) {
			$dbw->update( 'recentchanges', $set,
				array( /* WHERE */
					'rc_cur_id' => $current->getPage(),
					'rc_user_text' => $current->getUserText(),
					"rc_timestamp > '{$s->rev_timestamp}'",
				), __METHOD__
			);
		}

		# Generate the edit summary if necessary
		$target = Revision::newFromId( $s->rev_id );
		if ( empty( $summary ) ) {
			if ( $from == '' ) { // no public user name
				$summary = wfMsgForContent( 'revertpage-nouser' );
			} else {
				$summary = wfMsgForContent( 'revertpage' );
			}
		}

		# Allow the custom summary to use the same args as the default message
		$args = array(
			$target->getUserText(), $from, $s->rev_id,
			$wgLang->timeanddate( wfTimestamp( TS_MW, $s->rev_timestamp ), true ),
			$current->getId(), $wgLang->timeanddate( $current->getTimestamp() )
		);
		$summary = wfMsgReplaceArgs( $summary, $args );

		# Save
		$flags = EDIT_UPDATE;

		if ( $wgUser->isAllowed( 'minoredit' ) ) {
			$flags |= EDIT_MINOR;
		}

		if ( $bot && ( $wgUser->isAllowedAny( 'markbotedits', 'bot' ) ) ) {
			$flags |= EDIT_FORCE_BOT;
		}

		# Actually store the edit
		$status = $this->doEdit( $target->getText(), $summary, $flags, $target->getId() );
		if ( !empty( $status->value['revision'] ) ) {
			$revId = $status->value['revision']->getId();
		} else {
			$revId = false;
		}

		wfRunHooks( 'ArticleRollbackComplete', array( $this, $wgUser, $target, $current ) );

		$resultDetails = array(
			'summary' => $summary,
			'current' => $current,
			'target'  => $target,
			'newid'   => $revId
		);

		return array();
	}

	/**
	 * User interface for rollback operations
	 */
	public function rollback() {
		global $wgUser, $wgOut, $wgRequest;

		$details = null;

		$result = $this->doRollback(
			$wgRequest->getVal( 'from' ),
			$wgRequest->getText( 'summary' ),
			$wgRequest->getVal( 'token' ),
			$wgRequest->getBool( 'bot' ),
			$details
		);

		if ( in_array( array( 'actionthrottledtext' ), $result ) ) {
			$wgOut->rateLimited();
			return;
		}

		if ( isset( $result[0][0] ) && ( $result[0][0] == 'alreadyrolled' || $result[0][0] == 'cantrollback' ) ) {
			$wgOut->setPageTitle( wfMsg( 'rollbackfailed' ) );
			$errArray = $result[0];
			$errMsg = array_shift( $errArray );
			$wgOut->addWikiMsgArray( $errMsg, $errArray );

			if ( isset( $details['current'] ) ) {
				$current = $details['current'];

				if ( $current->getComment() != '' ) {
					$wgOut->addWikiMsgArray( 'editcomment', array(
						Linker::formatComment( $current->getComment() ) ), array( 'replaceafter' ) );
				}
			}

			return;
		}

		# Display permissions errors before read-only message -- there's no
		# point in misleading the user into thinking the inability to rollback
		# is only temporary.
		if ( !empty( $result ) && $result !== array( array( 'readonlytext' ) ) ) {
			# array_diff is completely broken for arrays of arrays, sigh.
			# Remove any 'readonlytext' error manually.
			$out = array();
			foreach ( $result as $error ) {
				if ( $error != array( 'readonlytext' ) ) {
					$out [] = $error;
				}
			}
			$wgOut->showPermissionsErrorPage( $out );

			return;
		}

		if ( $result == array( array( 'readonlytext' ) ) ) {
			$wgOut->readOnlyPage();

			return;
		}

		$current = $details['current'];
		$target = $details['target'];
		$newId = $details['newid'];
		$wgOut->setPageTitle( wfMsg( 'actioncomplete' ) );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );

		if ( $current->getUserText() === '' ) {
			$old = wfMsg( 'rev-deleted-user' );
		} else {
			$old = Linker::userLink( $current->getUser(), $current->getUserText() )
				. Linker::userToolLinks( $current->getUser(), $current->getUserText() );
		}

		$new = Linker::userLink( $target->getUser(), $target->getUserText() )
			. Linker::userToolLinks( $target->getUser(), $target->getUserText() );
		$wgOut->addHTML( wfMsgExt( 'rollback-success', array( 'parse', 'replaceafter' ), $old, $new ) );
		$wgOut->returnToMain( false, $this->mTitle );

		if ( !$wgRequest->getBool( 'hidediff', false ) && !$wgUser->getBoolOption( 'norollbackdiff', false ) ) {
			$de = new DifferenceEngine( $this->mTitle, $current->getId(), $newId, false, true );
			$de->showDiff( '', '' );
		}
	}

	/**
	 * Do standard deferred updates after page view
	 */
	public function viewUpdates() {
		global $wgDeferredUpdateList, $wgDisableCounters, $wgUser;
		if ( wfReadOnly() ) {
			return;
		}

		# Don't update page view counters on views from bot users (bug 14044)
		if ( !$wgDisableCounters && !$wgUser->isAllowed( 'bot' ) && $this->getID() ) {
			$wgDeferredUpdateList[] = new ViewCountUpdate( $this->getID() );
			$wgDeferredUpdateList[] = new SiteStatsUpdate( 1, 0, 0 );
		}

		# Update newtalk / watchlist notification status
		$wgUser->clearNotification( $this->mTitle );
	}

	/**
	 * Prepare text which is about to be saved.
	 * Returns a stdclass with source, pst and output members
	 */
	public function prepareTextForEdit( $text, $revid = null, User $user = null ) {
		if ( $this->mPreparedEdit && $this->mPreparedEdit->newText == $text && $this->mPreparedEdit->revid == $revid ) {
			// Already prepared
			return $this->mPreparedEdit;
		}

		global $wgParser;

		if( $user === null ) {
			global $wgUser;
			$user = $wgUser;
		}
		$popts = ParserOptions::newFromUser( $user );
		wfRunHooks( 'ArticlePrepareTextForEdit', array( $this, $popts ) );

		$edit = (object)array();
		$edit->revid = $revid;
		$edit->newText = $text;
		$edit->pst = $this->preSaveTransform( $text, $user, $popts );
		$edit->popts = $this->getParserOptions( true );
		$edit->output = $wgParser->parse( $edit->pst, $this->mTitle, $edit->popts, true, true, $revid );
		$edit->oldText = $this->getRawText();

		$this->mPreparedEdit = $edit;

		return $edit;
	}

	/**
	 * Do standard deferred updates after page edit.
	 * Update links tables, site stats, search index and message cache.
	 * Purges pages that include this page if the text was changed here.
	 * Every 100th edit, prune the recent changes table.
	 *
	 * @private
	 * @param $text String: New text of the article
	 * @param $summary String: Edit summary
	 * @param $minoredit Boolean: Minor edit
	 * @param $timestamp_of_pagechange String timestamp associated with the page change
	 * @param $newid Integer: rev_id value of the new revision
	 * @param $changed Boolean: Whether or not the content actually changed
	 * @param $user User object: User doing the edit
	 * @param $created Boolean: Whether the edit created the page
	 */
	public function editUpdates( $text, $summary, $minoredit, $timestamp_of_pagechange, $newid,
		$changed = true, User $user = null, $created = false )
	{
		global $wgDeferredUpdateList, $wgUser, $wgEnableParserCache;

		wfProfileIn( __METHOD__ );

		# Parse the text
		# Be careful not to double-PST: $text is usually already PST-ed once
		if ( !$this->mPreparedEdit || $this->mPreparedEdit->output->getFlag( 'vary-revision' ) ) {
			wfDebug( __METHOD__ . ": No prepared edit or vary-revision is set...\n" );
			$editInfo = $this->prepareTextForEdit( $text, $newid, $user );
		} else {
			wfDebug( __METHOD__ . ": No vary-revision, using prepared edit...\n" );
			$editInfo = $this->mPreparedEdit;
		}

		# Save it to the parser cache
		if ( $wgEnableParserCache ) {
			$parserCache = ParserCache::singleton();
			$parserCache->save( $editInfo->output, $this, $editInfo->popts );
		}

		# Update the links tables
		$u = new LinksUpdate( $this->mTitle, $editInfo->output );
		$u->doUpdate();

		wfRunHooks( 'ArticleEditUpdates', array( &$this, &$editInfo, $changed ) );

		if ( wfRunHooks( 'ArticleEditUpdatesDeleteFromRecentchanges', array( &$this ) ) ) {
			if ( 0 == mt_rand( 0, 99 ) ) {
				// Flush old entries from the `recentchanges` table; we do this on
				// random requests so as to avoid an increase in writes for no good reason
				global $wgRCMaxAge;

				$dbw = wfGetDB( DB_MASTER );
				$cutoff = $dbw->timestamp( time() - $wgRCMaxAge );
				$dbw->delete(
					'recentchanges',
					array( "rc_timestamp < '$cutoff'" ),
					__METHOD__
				);
			}
		}

		$id = $this->getID();
		$title = $this->mTitle->getPrefixedDBkey();
		$shortTitle = $this->mTitle->getDBkey();

		if ( 0 == $id ) {
			wfProfileOut( __METHOD__ );
			return;
		}

		if ( !$changed ) {
			$good = 0;
			$total = 0;
		} elseif ( $created ) {
			$good = (int)$this->isCountable( $editInfo );
			$total = 1;
		} else {
			$good = (int)$this->isCountable( $editInfo ) - (int)$this->isCountable();
			$total = 0;
		}

		$wgDeferredUpdateList[] = new SiteStatsUpdate( 0, 1, $good, $total );
		$wgDeferredUpdateList[] = new SearchUpdate( $id, $title, $text );

		# If this is another user's talk page, update newtalk
		# Don't do this if $changed = false otherwise some idiot can null-edit a
		# load of user talk pages and piss people off, nor if it's a minor edit
		# by a properly-flagged bot.
		if ( $this->mTitle->getNamespace() == NS_USER_TALK && $shortTitle != $wgUser->getTitleKey() && $changed
			&& !( $minoredit && $wgUser->isAllowed( 'nominornewtalk' ) )
		) {
			if ( wfRunHooks( 'ArticleEditUpdateNewTalk', array( &$this ) ) ) {
				$other = User::newFromName( $shortTitle, false );
				if ( !$other ) {
					wfDebug( __METHOD__ . ": invalid username\n" );
				} elseif ( User::isIP( $shortTitle ) ) {
					// An anonymous user
					$other->setNewtalk( true );
				} elseif ( $other->isLoggedIn() ) {
					$other->setNewtalk( true );
				} else {
					wfDebug( __METHOD__ . ": don't need to notify a nonexistent user\n" );
				}
			}
		}

		if ( $this->mTitle->getNamespace() == NS_MEDIAWIKI ) {
			MessageCache::singleton()->replace( $shortTitle, $text );
		}

		wfProfileOut( __METHOD__ );
	}

	/**
	 * Perform article updates on a special page creation.
	 *
	 * @param $rev Revision object
	 *
	 * @todo This is a shitty interface function. Kill it and replace the
	 * other shitty functions like editUpdates and such so it's not needed
	 * anymore.
	 */
	public function createUpdates( $rev ) {
		$this->editUpdates( $rev->getText(), $rev->getComment(),
			$rev->isMinor(), wfTimestamp(), $rev->getId(), true, null, true );
	}

	/**
	 * Generate the navigation links when browsing through an article revisions
	 * It shows the information as:
	 *   Revision as of \<date\>; view current revision
	 *   \<- Previous version | Next Version -\>
	 *
	 * @param $oldid String: revision ID of this article revision
	 */
	public function setOldSubtitle( $oldid = 0 ) {
		global $wgLang, $wgOut, $wgUser, $wgRequest;

		if ( !wfRunHooks( 'DisplayOldSubtitle', array( &$this, &$oldid ) ) ) {
			return;
		}

		$unhide = $wgRequest->getInt( 'unhide' ) == 1;

		# Cascade unhide param in links for easy deletion browsing
		$extraParams = array();
		if ( $wgRequest->getVal( 'unhide' ) ) {
			$extraParams['unhide'] = 1;
		}

		$revision = Revision::newFromId( $oldid );
		$timestamp = $revision->getTimestamp();

		$current = ( $oldid == $this->mLatest );
		$td = $wgLang->timeanddate( $timestamp, true );
		$tddate = $wgLang->date( $timestamp, true );
		$tdtime = $wgLang->time( $timestamp, true );

		$lnk = $current
			? wfMsgHtml( 'currentrevisionlink' )
			: Linker::link(
				$this->mTitle,
				wfMsgHtml( 'currentrevisionlink' ),
				array(),
				$extraParams,
				array( 'known', 'noclasses' )
			);
		$curdiff = $current
			? wfMsgHtml( 'diff' )
			: Linker::link(
				$this->mTitle,
				wfMsgHtml( 'diff' ),
				array(),
				array(
					'diff' => 'cur',
					'oldid' => $oldid
				) + $extraParams,
				array( 'known', 'noclasses' )
			);
		$prev = $this->mTitle->getPreviousRevisionID( $oldid ) ;
		$prevlink = $prev
			? Linker::link(
				$this->mTitle,
				wfMsgHtml( 'previousrevision' ),
				array(),
				array(
					'direction' => 'prev',
					'oldid' => $oldid
				) + $extraParams,
				array( 'known', 'noclasses' )
			)
			: wfMsgHtml( 'previousrevision' );
		$prevdiff = $prev
			? Linker::link(
				$this->mTitle,
				wfMsgHtml( 'diff' ),
				array(),
				array(
					'diff' => 'prev',
					'oldid' => $oldid
				) + $extraParams,
				array( 'known', 'noclasses' )
			)
			: wfMsgHtml( 'diff' );
		$nextlink = $current
			? wfMsgHtml( 'nextrevision' )
			: Linker::link(
				$this->mTitle,
				wfMsgHtml( 'nextrevision' ),
				array(),
				array(
					'direction' => 'next',
					'oldid' => $oldid
				) + $extraParams,
				array( 'known', 'noclasses' )
			);
		$nextdiff = $current
			? wfMsgHtml( 'diff' )
			: Linker::link(
				$this->mTitle,
				wfMsgHtml( 'diff' ),
				array(),
				array(
					'diff' => 'next',
					'oldid' => $oldid
				) + $extraParams,
				array( 'known', 'noclasses' )
			);

		$cdel = '';

		// User can delete revisions or view deleted revisions...
		$canHide = $wgUser->isAllowed( 'deleterevision' );
		if ( $canHide || ( $revision->getVisibility() && $wgUser->isAllowed( 'deletedhistory' ) ) ) {
			if ( !$revision->userCan( Revision::DELETED_RESTRICTED ) ) {
				$cdel = Linker::revDeleteLinkDisabled( $canHide ); // rev was hidden from Sysops
			} else {
				$query = array(
					'type'   => 'revision',
					'target' => $this->mTitle->getPrefixedDbkey(),
					'ids'    => $oldid
				);
				$cdel = Linker::revDeleteLink( $query, $revision->isDeleted( File::DELETED_RESTRICTED ), $canHide );
			}
			$cdel .= ' ';
		}

		# Show user links if allowed to see them. If hidden, then show them only if requested...
		$userlinks = Linker::revUserTools( $revision, !$unhide );

		$infomsg = $current && !wfMessage( 'revision-info-current' )->isDisabled()
			? 'revision-info-current'
			: 'revision-info';

		$r = "\n\t\t\t\t<div id=\"mw-{$infomsg}\">" .
			wfMsgExt(
				$infomsg,
				array( 'parseinline', 'replaceafter' ),
				$td,
				$userlinks,
				$revision->getID(),
				$tddate,
				$tdtime,
				$revision->getUser()
			) .
			"</div>\n" .
			"\n\t\t\t\t<div id=\"mw-revision-nav\">" . $cdel . wfMsgExt( 'revision-nav', array( 'escapenoentities', 'parsemag', 'replaceafter' ),
			$prevdiff, $prevlink, $lnk, $curdiff, $nextlink, $nextdiff ) . "</div>\n\t\t\t";

		$wgOut->addHTML( $r );
	}

	/**
	 * This function is called right before saving the wikitext,
	 * so we can do things like signatures and links-in-context.
	 *
	 * @param $text String article contents
	 * @param $user User object: user doing the edit, $wgUser will be used if
	 *              null is given
	 * @param $popts ParserOptions object: parser options, default options for
	 *               the user loaded if null given
	 * @return string article contents with altered wikitext markup (signatures
	 * 	converted, {{subst:}}, templates, etc.)
	 */
	public function preSaveTransform( $text, User $user = null, ParserOptions $popts = null ) {
		global $wgParser;

		if ( $user === null ) {
			global $wgUser;
			$user = $wgUser;
		}

		if ( $popts === null ) {
			$popts = ParserOptions::newFromUser( $user );
		}

		return $wgParser->preSaveTransform( $text, $this->mTitle, $user, $popts );
	}

	/* Caching functions */

	/**
	 * checkLastModified returns true if it has taken care of all
	 * output to the client that is necessary for this request.
	 * (that is, it has sent a cached version of the page)
	 *
	 * @return boolean true if cached version send, false otherwise
	 */
	protected function tryFileCache() {
		static $called = false;

		if ( $called ) {
			wfDebug( "Article::tryFileCache(): called twice!?\n" );
			return false;
		}

		$called = true;
		if ( $this->isFileCacheable() ) {
			$cache = new HTMLFileCache( $this->mTitle );
			if ( $cache->isFileCacheGood( $this->mTouched ) ) {
				wfDebug( "Article::tryFileCache(): about to load file\n" );
				$cache->loadFromFileCache();
				return true;
			} else {
				wfDebug( "Article::tryFileCache(): starting buffer\n" );
				ob_start( array( &$cache, 'saveToFileCache' ) );
			}
		} else {
			wfDebug( "Article::tryFileCache(): not cacheable\n" );
		}

		return false;
	}

	/**
	 * Check if the page can be cached
	 * @return bool
	 */
	public function isFileCacheable() {
		$cacheable = false;

		if ( HTMLFileCache::useFileCache() ) {
			$cacheable = $this->getID() && !$this->mRedirectedFrom && !$this->mTitle->isRedirect();
			// Extension may have reason to disable file caching on some pages.
			if ( $cacheable ) {
				$cacheable = wfRunHooks( 'IsFileCacheable', array( &$this ) );
			}
		}

		return $cacheable;
	}

	/**
	 * Loads page_touched and returns a value indicating if it should be used
	 * @return boolean true if not a redirect
	 */
	public function checkTouched() {
		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}

		return !$this->mIsRedirect;
	}

	/**
	 * Get the page_touched field
	 * @return string containing GMT timestamp
	 */
	public function getTouched() {
		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}

		return $this->mTouched;
	}

	/**
	 * Get the page_latest field
	 * @return integer rev_id of current revision
	 */
	public function getLatest() {
		if ( !$this->mDataLoaded ) {
			$this->loadPageData();
		}

		return (int)$this->mLatest;
	}

	/**
	 * Edit an article without doing all that other stuff
	 * The article must already exist; link tables etc
	 * are not updated, caches are not flushed.
	 *
	 * @param $text String: text submitted
	 * @param $comment String: comment submitted
	 * @param $minor Boolean: whereas it's a minor modification
	 */
	public function quickEdit( $text, $comment = '', $minor = 0 ) {
		wfProfileIn( __METHOD__ );

		$dbw = wfGetDB( DB_MASTER );
		$revision = new Revision( array(
			'page'       => $this->getId(),
			'text'       => $text,
			'comment'    => $comment,
			'minor_edit' => $minor ? 1 : 0,
			) );
		$revision->insertOn( $dbw );
		$this->updateRevisionOn( $dbw, $revision );

		global $wgUser;
		wfRunHooks( 'NewRevisionFromEditComplete', array( $this, $revision, false, $wgUser ) );

		wfProfileOut( __METHOD__ );
	}

	/**
	 * The onArticle*() functions are supposed to be a kind of hooks
	 * which should be called whenever any of the specified actions
	 * are done.
	 *
	 * This is a good place to put code to clear caches, for instance.
	 *
	 * This is called on page move and undelete, as well as edit
	 *
	 * @param $title Title object
	 */
	public static function onArticleCreate( $title ) {
		# Update existence markers on article/talk tabs...
		if ( $title->isTalkPage() ) {
			$other = $title->getSubjectPage();
		} else {
			$other = $title->getTalkPage();
		}

		$other->invalidateCache();
		$other->purgeSquid();

		$title->touchLinks();
		$title->purgeSquid();
		$title->deleteTitleProtection();
	}

	/**
	 * Clears caches when article is deleted
	 *
	 * @param $title Title
	 */
	public static function onArticleDelete( $title ) {
		# Update existence markers on article/talk tabs...
		if ( $title->isTalkPage() ) {
			$other = $title->getSubjectPage();
		} else {
			$other = $title->getTalkPage();
		}

		$other->invalidateCache();
		$other->purgeSquid();

		$title->touchLinks();
		$title->purgeSquid();

		# File cache
		HTMLFileCache::clearFileCache( $title );

		# Messages
		if ( $title->getNamespace() == NS_MEDIAWIKI ) {
			MessageCache::singleton()->replace( $title->getDBkey(), false );
		}

		# Images
		if ( $title->getNamespace() == NS_FILE ) {
			$update = new HTMLCacheUpdate( $title, 'imagelinks' );
			$update->doUpdate();
		}

		# User talk pages
		if ( $title->getNamespace() == NS_USER_TALK ) {
			$user = User::newFromName( $title->getText(), false );
			$user->setNewtalk( false );
		}

		# Image redirects
		RepoGroup::singleton()->getLocalRepo()->invalidateImageRedirect( $title );
	}

	/**
	 * Purge caches on page update etc
	 *
	 * @param $title Title object
	 * @todo:  verify that $title is always a Title object (and never false or null), add Title hint to parameter $title
	 */
	public static function onArticleEdit( $title ) {
		global $wgDeferredUpdateList;

		// Invalidate caches of articles which include this page
		$wgDeferredUpdateList[] = new HTMLCacheUpdate( $title, 'templatelinks' );

		// Invalidate the caches of all pages which redirect here
		$wgDeferredUpdateList[] = new HTMLCacheUpdate( $title, 'redirect' );

		# Purge squid for this page only
		$title->purgeSquid();

		# Clear file cache for this page only
		HTMLFileCache::clearFileCache( $title );
	}

	/**#@-*/

	/**
	 * Overriden by ImagePage class, only present here to avoid a fatal error
	 * Called for ?action=revert
	 */
	public function revert() {
		global $wgOut;
		$wgOut->showErrorPage( 'nosuchaction', 'nosuchactiontext' );
	}

	/**
	 * Return a list of templates used by this article.
	 * Uses the templatelinks table
	 *
	 * @return Array of Title objects
	 */
	public function getUsedTemplates() {
		$result = array();
		$id = $this->mTitle->getArticleID();

		if ( $id == 0 ) {
			return array();
		}

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( array( 'templatelinks' ),
			array( 'tl_namespace', 'tl_title' ),
			array( 'tl_from' => $id ),
			__METHOD__ );

		if ( $res !== false ) {
			foreach ( $res as $row ) {
				$result[] = Title::makeTitle( $row->tl_namespace, $row->tl_title );
			}
		}

		return $result;
	}

	/**
	 * Returns a list of hidden categories this page is a member of.
	 * Uses the page_props and categorylinks tables.
	 *
	 * @return Array of Title objects
	 */
	public function getHiddenCategories() {
		$result = array();
		$id = $this->mTitle->getArticleID();

		if ( $id == 0 ) {
			return array();
		}

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( array( 'categorylinks', 'page_props', 'page' ),
			array( 'cl_to' ),
			array( 'cl_from' => $id, 'pp_page=page_id', 'pp_propname' => 'hiddencat',
				'page_namespace' => NS_CATEGORY, 'page_title=cl_to' ),
			__METHOD__ );

		if ( $res !== false ) {
			foreach ( $res as $row ) {
				$result[] = Title::makeTitle( NS_CATEGORY, $row->cl_to );
			}
		}

		return $result;
	}

	/**
	* Return an applicable autosummary if one exists for the given edit.
	* @param $oldtext String: the previous text of the page.
	* @param $newtext String: The submitted text of the page.
	* @param $flags Int bitmask: a bitmask of flags submitted for the edit.
	* @return string An appropriate autosummary, or an empty string.
	*/
	public static function getAutosummary( $oldtext, $newtext, $flags ) {
		global $wgContLang;

		# Decide what kind of autosummary is needed.

		# Redirect autosummaries
		$ot = Title::newFromRedirect( $oldtext );
		$rt = Title::newFromRedirect( $newtext );

		if ( is_object( $rt ) && ( !is_object( $ot ) || !$rt->equals( $ot ) || $ot->getFragment() != $rt->getFragment() ) ) {
			return wfMsgForContent( 'autoredircomment', $rt->getFullText() );
		}

		# New page autosummaries
		if ( $flags & EDIT_NEW && strlen( $newtext ) ) {
			# If they're making a new article, give its text, truncated, in the summary.

			$truncatedtext = $wgContLang->truncate(
				str_replace( "\n", ' ', $newtext ),
				max( 0, 200 - strlen( wfMsgForContent( 'autosumm-new' ) ) ) );

			return wfMsgForContent( 'autosumm-new', $truncatedtext );
		}

		# Blanking autosummaries
		if ( $oldtext != '' && $newtext == '' ) {
			return wfMsgForContent( 'autosumm-blank' );
		} elseif ( strlen( $oldtext ) > 10 * strlen( $newtext ) && strlen( $newtext ) < 500 ) {
			# Removing more than 90% of the article

			$truncatedtext = $wgContLang->truncate(
				$newtext,
				max( 0, 200 - strlen( wfMsgForContent( 'autosumm-replace' ) ) ) );

			return wfMsgForContent( 'autosumm-replace', $truncatedtext );
		}

		# If we reach this point, there's no applicable autosummary for our case, so our
		# autosummary is empty.
		return '';
	}

	/**
	 * Add the primary page-view wikitext to the output buffer
	 * Saves the text into the parser cache if possible.
	 * Updates templatelinks if it is out of date.
	 *
	 * @param $text String
	 * @param $cache Boolean
	 * @param $parserOptions mixed ParserOptions object, or boolean false
	 */
	public function outputWikiText( $text, $cache = true, $parserOptions = false ) {
		global $wgOut;

		$this->mParserOutput = $this->getOutputFromWikitext( $text, $cache, $parserOptions );
		$wgOut->addParserOutput( $this->mParserOutput );
	}

	/**
	 * This does all the heavy lifting for outputWikitext, except it returns the parser
	 * output instead of sending it straight to $wgOut. Makes things nice and simple for,
	 * say, embedding thread pages within a discussion system (LiquidThreads)
	 *
	 * @param $text string
	 * @param $cache boolean
	 * @param $parserOptions parsing options, defaults to false
	 * @return string containing parsed output
	 */
	public function getOutputFromWikitext( $text, $cache = true, $parserOptions = false ) {
		global $wgParser, $wgEnableParserCache, $wgUseFileCache;

		if ( !$parserOptions ) {
			$parserOptions = $this->getParserOptions();
		}

		$time = - wfTime();
		$this->mParserOutput = $wgParser->parse( $text, $this->mTitle,
			$parserOptions, true, true, $this->getRevIdFetched() );
		$time += wfTime();

		# Timing hack
		if ( $time > 3 ) {
			wfDebugLog( 'slow-parse', sprintf( "%-5.2f %s", $time,
				$this->mTitle->getPrefixedDBkey() ) );
		}

		if ( $wgEnableParserCache && $cache && $this->mParserOutput->isCacheable() ) {
			$parserCache = ParserCache::singleton();
			$parserCache->save( $this->mParserOutput, $this, $parserOptions );
		}

		// Make sure file cache is not used on uncacheable content.
		// Output that has magic words in it can still use the parser cache
		// (if enabled), though it will generally expire sooner.
		if ( !$this->mParserOutput->isCacheable() || $this->mParserOutput->containsOldMagic() ) {
			$wgUseFileCache = false;
		}

		$this->doCascadeProtectionUpdates( $this->mParserOutput );

		return $this->mParserOutput;
	}

	/**
	 * Get parser options suitable for rendering the primary article wikitext
	 * @param $canonical boolean Determines that the generated options must not depend on user preferences (see bug 14404)
	 * @return mixed ParserOptions object or boolean false
	 */
	public function getParserOptions( $canonical = false ) {
		global $wgUser, $wgLanguageCode;

		if ( !$this->mParserOptions || $canonical ) {
			$user = !$canonical ? $wgUser : new User;
			$parserOptions = new ParserOptions( $user );
			$parserOptions->setTidy( true );
			$parserOptions->enableLimitReport();

			if ( $canonical ) {
				$parserOptions->setUserLang( $wgLanguageCode ); # Must be set explicitely
				return $parserOptions;
			}
			$this->mParserOptions = $parserOptions;
		}
		// Clone to allow modifications of the return value without affecting cache
		return clone $this->mParserOptions;
	}

	/**
	* Get parser options suitable for rendering the primary article wikitext
	* @param User $user
	* @return ParserOptions
	*/
	public function makeParserOptions( User $user ) {
		$options = ParserOptions::newFromUser( $user );
		$options->enableLimitReport(); // show inclusion/loop reports
		$options->setTidy( true ); // fix bad HTML
		return $options;
	}

	/**
	 * Updates cascading protections
	 *
	 * @param $parserOutput ParserOutput object, or boolean false
	 **/
	protected function doCascadeProtectionUpdates( $parserOutput ) {
		if ( !$this->isCurrent() || wfReadOnly() || !$this->mTitle->areRestrictionsCascading() ) {
			return;
		}

		// templatelinks table may have become out of sync,
		// especially if using variable-based transclusions.
		// For paranoia, check if things have changed and if
		// so apply updates to the database. This will ensure
		// that cascaded protections apply as soon as the changes
		// are visible.

		# Get templates from templatelinks
		$id = $this->mTitle->getArticleID();

		$tlTemplates = array();

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select( array( 'templatelinks' ),
			array( 'tl_namespace', 'tl_title' ),
			array( 'tl_from' => $id ),
			__METHOD__
		);

		foreach ( $res as $row ) {
			$tlTemplates["{$row->tl_namespace}:{$row->tl_title}"] = true;
		}

		# Get templates from parser output.
		$poTemplates = array();
		foreach ( $parserOutput->getTemplates() as $ns => $templates ) {
			foreach ( $templates as $dbk => $id ) {
				$poTemplates["$ns:$dbk"] = true;
			}
		}

		# Get the diff
		$templates_diff = array_diff_key( $poTemplates, $tlTemplates );

		if ( count( $templates_diff ) > 0 ) {
			# Whee, link updates time.
			$u = new LinksUpdate( $this->mTitle, $parserOutput, false );
			$u->doUpdate();
		}
	}

	/**
	 * Update all the appropriate counts in the category table, given that
	 * we've added the categories $added and deleted the categories $deleted.
	 *
	 * @param $added array   The names of categories that were added
	 * @param $deleted array The names of categories that were deleted
	 */
	public function updateCategoryCounts( $added, $deleted ) {
		$ns = $this->mTitle->getNamespace();
		$dbw = wfGetDB( DB_MASTER );

		# First make sure the rows exist.  If one of the "deleted" ones didn't
		# exist, we might legitimately not create it, but it's simpler to just
		# create it and then give it a negative value, since the value is bogus
		# anyway.
		#
		# Sometimes I wish we had INSERT ... ON DUPLICATE KEY UPDATE.
		$insertCats = array_merge( $added, $deleted );
		if ( !$insertCats ) {
			# Okay, nothing to do
			return;
		}

		$insertRows = array();

		foreach ( $insertCats as $cat ) {
			$insertRows[] = array(
				'cat_id' => $dbw->nextSequenceValue( 'category_cat_id_seq' ),
				'cat_title' => $cat
			);
		}
		$dbw->insert( 'category', $insertRows, __METHOD__, 'IGNORE' );

		$addFields    = array( 'cat_pages = cat_pages + 1' );
		$removeFields = array( 'cat_pages = cat_pages - 1' );

		if ( $ns == NS_CATEGORY ) {
			$addFields[]    = 'cat_subcats = cat_subcats + 1';
			$removeFields[] = 'cat_subcats = cat_subcats - 1';
		} elseif ( $ns == NS_FILE ) {
			$addFields[]    = 'cat_files = cat_files + 1';
			$removeFields[] = 'cat_files = cat_files - 1';
		}

		if ( $added ) {
			$dbw->update(
				'category',
				$addFields,
				array( 'cat_title' => $added ),
				__METHOD__
			);
		}

		if ( $deleted ) {
			$dbw->update(
				'category',
				$removeFields,
				array( 'cat_title' => $deleted ),
				__METHOD__
			);
		}
	}

	/**
	 * Lightweight method to get the parser output for a page, checking the parser cache
	 * and so on. Doesn't consider most of the stuff that Article::view is forced to
	 * consider, so it's not appropriate to use there.
	 *
	 * @since 1.16 (r52326) for LiquidThreads
	 *
	 * @param $oldid mixed integer Revision ID or null
	 * @return ParserOutput or false if the given revsion ID is not found
	 */
	public function getParserOutput( $oldid = null ) {
		global $wgEnableParserCache, $wgUser;

		// Should the parser cache be used?
		$useParserCache = $wgEnableParserCache &&
			$wgUser->getStubThreshold() == 0 &&
			$this->exists() &&
			$oldid === null;

		wfDebug( __METHOD__ . ': using parser cache: ' . ( $useParserCache ? 'yes' : 'no' ) . "\n" );

		if ( $wgUser->getStubThreshold() ) {
			wfIncrStats( 'pcache_miss_stub' );
		}

		if ( $useParserCache ) {
			$parserOutput = ParserCache::singleton()->get( $this, $this->getParserOptions() );
			if ( $parserOutput !== false ) {
				return $parserOutput;
			}
		}

		// Cache miss; parse and output it.
		if ( $oldid === null ) {
			$text = $this->getRawText();
		} else {
			$rev = Revision::newFromTitle( $this->getTitle(), $oldid );
			if ( $rev === null ) {
				return false;
			}
			$text = $rev->getText();
		}

		return $this->getOutputFromWikitext( $text, $useParserCache );
	}

	/**
	 * Sets the context this Article is executed in
	 *
	 * @param $context RequestContext
	 * @since 1.18
	 */
	public function setContext( $context ) {
		$this->mContext = $context;
	}

	/**
	 * Gets the context this Article is executed in
	 *
	 * @return RequestContext
	 * @since 1.18
	 */
	public function getContext() {
		if ( $this->mContext instanceof RequestContext ) {
			return $this->mContext;
		} else {
			wfDebug( __METHOD__ . " called and \$mContext is null. Return RequestContext::getMain(); for sanity\n" );
			return RequestContext::getMain();
		}
	}

}

class PoolWorkArticleView extends PoolCounterWork {

	/**
	 * @var Article
	 */
	private $mArticle;

	function __construct( $article, $key, $useParserCache, $parserOptions ) {
		parent::__construct( 'ArticleView', $key );
		$this->mArticle = $article;
		$this->cacheable = $useParserCache;
		$this->parserOptions = $parserOptions;
	}

	function doWork() {
		return $this->mArticle->doViewParse();
	}

	function getCachedWork() {
		global $wgOut;

		$parserCache = ParserCache::singleton();
		$this->mArticle->mParserOutput = $parserCache->get( $this->mArticle, $this->parserOptions );

		if ( $this->mArticle->mParserOutput !== false ) {
			wfDebug( __METHOD__ . ": showing contents parsed by someone else\n" );
			$wgOut->addParserOutput( $this->mArticle->mParserOutput );
			# Ensure that UI elements requiring revision ID have
			# the correct version information.
			$wgOut->setRevisionId( $this->mArticle->getLatest() );
			return true;
		}
		return false;
	}

	function fallback() {
		return $this->mArticle->tryDirtyCache();
	}

	/**
	 * @param  $status Status
	 */
	function error( $status ) {
		global $wgOut;

		$wgOut->clearHTML(); // for release() errors
		$wgOut->enableClientCache( false );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );

		$errortext = $status->getWikiText( false, 'view-pool-error' );
		$wgOut->addWikiText( '<div class="errorbox">' . $errortext . '</div>' );

		return false;
	}
}
