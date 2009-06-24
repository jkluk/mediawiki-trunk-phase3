<?php
/**
 * Erase a page record from the database
 * Irreversible (can't use standard undelete) and does not update link tables
 *
 * @file
 * @ingroup Maintenance
 * @author Rob Church <robchur@gmail.com>
 */

require_once( "Maintenance.php" );

class NukePage extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Remove a page record from the database";
		$this->addParam( 'delete', "Actually delete the page" );
		$this->addArgs( array( 'title' ) );
	}

	public function execute() {

		$name = $this->getArg();
		$delete = $this->getOption( 'delete', false );

		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin();

		$tbl_pag = $dbw->tableName( 'page' );
		$tbl_rec = $dbw->tableName( 'recentchanges' );
		$tbl_rev = $dbw->tableName( 'revision' );

		# Get page ID
		$this->output( "Searching for \"$name\"..." );
		$title = Title::newFromText( $name );
		if( $title ) {
			$id   = $title->getArticleID();
			$real = $title->getPrefixedText();
			$isGoodArticle = $title->isContentPage();
			$this->output( "found \"$real\" with ID $id.\n" );

			# Get corresponding revisions
			$this->output( "Searching for revisions..." );
			$res = $dbw->query( "SELECT rev_id FROM $tbl_rev WHERE rev_page = $id" );
			while( $row = $dbw->fetchObject( $res ) ) {
				$revs[] = $row->rev_id;
			}
			$count = count( $revs );
			$this->output( "found $count.\n" );

			# Delete the page record and associated recent changes entries
			if( $delete ) {
				$this->output( "Deleting page record..." );
				$dbw->query( "DELETE FROM $tbl_pag WHERE page_id = $id" );
				$this->output( "done.\n" );
				$this->output( "Cleaning up recent changes..." );
				$dbw->query( "DELETE FROM $tbl_rec WHERE rc_cur_id = $id" );
				$this->output( "done.\n" );
			}

			$dbw->commit();

			# Delete revisions as appropriate
			if( $delete && $count ) {
				$this->output( "Deleting revisions..." );
				$this->deleteRevisions( $revs );
				$this->output( "done.\n" );
				$this->purgeRedundantText( true );
			}

			# Update stats as appropriate
			if ( $delete ) {
				$this->output( "Updating site stats..." );
				$ga = $isGoodArticle ? -1 : 0; // if it was good, decrement that too
				$stats = new SiteStatsUpdate( 0, -$count, $ga, -1 );
				$stats->doUpdate();
				$this->output( "done.\n" );
			}
		} else {
			$this->output( "not found in database.\n" );
			$dbw->commit();
		}
	}

	public function deleteRevisions( $ids ) {
		$dbw = wfGetDB( DB_MASTER );
		$dbw->begin();

		$tbl_rev = $dbw->tableName( 'revision' );

		$set = implode( ', ', $ids );
		$dbw->query( "DELETE FROM $tbl_rev WHERE rev_id IN ( $set )" );

		$dbw->commit();	
	}
}

$maintClass = "NukePage";
require_once( DO_MAINTENANCE );