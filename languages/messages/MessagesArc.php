<?php
/** Aramaic (ܐܪܡܝܐ)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author 334a
 * @author A2raya07
 * @author Basharh
 * @author The Thadman
 */

$specialPageAliases = array(
	'CreateAccount'             => array( 'ܒܪܝ_ܚܘܫܒܢܐ' ),
	'Preferences'               => array( 'ܓܒܝܬ̈ܐ' ),
	'Watchlist'                 => array( 'ܪ̈ܗܝܬܐ' ),
	'Recentchanges'             => array( 'ܫܘܚܠܦ̈ܐ_ܚܕܬ̈ܐ' ),
	'Upload'                    => array( 'ܐܣܩ' ),
	'Shortpages'                => array( 'ܦܐܬܬ̈ܐ_ܟܪ̈ܝܬܐ' ),
	'Longpages'                 => array( 'ܦܐܬܬ̈ܐ_ܐܪ̈ܝܟܬܐ' ),
	'Newpages'                  => array( 'ܦܐܬܬ̈ܐ_ܚܕܬܬ̈ܐ' ),
	'Protectedpages'            => array( 'ܦܐܬܬ̈ܐ_ܢܛܝܪ̈ܬܐ' ),
	'Allpages'                  => array( 'ܟܠ_ܦܐܬܬ̈ܐ' ),
	'Specialpages'              => array( 'ܦܐܬܬ̈ܐ_ܕܝܠܢܝܬ̈ܐ' ),
	'Contributions'             => array( 'ܫܘܬܦܘܝܬ̈ܐ' ),
	'Whatlinkshere'             => array( 'ܡܐ_ܐܣܪ_ܠܟܐ' ),
	'Movepage'                  => array( 'ܫܢܝ_ܦܐܬܐ' ),
	'Categories'                => array( 'ܣܕܪ̈ܐ' ),
	'Allmessages'               => array( 'ܟܠ_ܐܓܪ̈ܬܐ' ),
	'Log'                       => array( 'ܣܓܠ̈ܐ' ),
	'Mycontributions'           => array( 'ܫܘܬܦܘܝܬ̈ܝ' ),
	'Search'                    => array( 'ܒܨܝܐ' ),
	'Filepath'                  => array( 'ܫܒܝܠܐ_ܕܦܐܬܐ' ),
	'Blankpage'                 => array( 'ܦܐܬܐ_ܣܦܝܩܬܐ' ),
	'DeletedContributions'      => array( 'ܫܘܬܦܘܝܬ̈ܐ_ܫܝܦܬ̈ܐ' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#ܨܘܝܒܐ', '#REDIRECT' ),
	'numberofpages'         => array( '1', 'ܡܢܝܢܐ_ܕܦܐܬܬ̈ܐ', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'ܡܢܝܢܐ_ܕܡܠܘܐ̈ܐ', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'ܡܢܝܢܐ_ܕܠܦܦ̈ܐ', 'NUMBEROFFILES' ),
	'pagename'              => array( '1', 'ܫܡܐ_ܕܦܐܬܐ', 'PAGENAME' ),
	'msg'                   => array( '0', 'ܐܓܪܬܐ:', 'MSG:' ),
	'img_thumbnail'         => array( '1', 'ܙܥܘܪܬܐ', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'ܙܥܘܪܬܐ=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'ܝܡܝܢܐ', 'right' ),
	'img_left'              => array( '1', 'ܣܡܠܐ', 'left' ),
	'img_none'              => array( '1', 'ܠܐ ܡܕܡ', 'none' ),
	'img_center'            => array( '1', 'ܡܨܥܐ', 'center', 'centre' ),
	'grammar'               => array( '0', 'ܬܘܪܨ_ܡܡܠܠܐ', 'GRAMMAR:' ),
	'language'              => array( '0', '#ܠܫܢܐ:', '#LANGUAGE:' ),
	'special'               => array( '0', 'ܕܝܠܢܝܐ', 'special' ),
);

$rtl = true;

$defaultUserOptionOverrides = array(
	# Swap sidebar to right side by default
	'quickbar' => 2,
);

$namespaceNames = array(
	NS_MEDIA            => 'ܡܝܕܝܐ',
	NS_SPECIAL          => 'ܕܝܠܢܝܐ',
	NS_MAIN             => '',
	NS_TALK             => 'ܡܡܠܠܐ',
	NS_USER             => 'ܡܬܚܫܚܢܐ',
	NS_USER_TALK        => 'ܡܡܠܠܐ_ܕܡܬܚܫܚܢܐ',
	NS_PROJECT_TALK     => 'ܡܡܠܠܐ_ܕ$1',
	NS_FILE             => 'ܠܦܦܐ',
	NS_FILE_TALK        => 'ܡܡܠܠܐ_ܕܠܦܦܐ',
	NS_MEDIAWIKI        => 'ܡܝܕܝܐܘܝܩܝ',
	NS_MEDIAWIKI_TALK   => 'ܡܡܠܠܐ_ܕܡܝܕܝܐܘܝܩܝ',
	NS_TEMPLATE         => 'ܩܠܒܐ',
	NS_TEMPLATE_TALK    => 'ܡܡܠܠܐ_ܕܩܠܒܐ',
	NS_HELP             => 'ܥܘܕܪܢܐ',
	NS_HELP_TALK        => 'ܡܡܠܠܐ_ܕܥܘܕܪܢܐ',
	NS_CATEGORY         => 'ܣܕܪܐ',
	NS_CATEGORY_TALK    => 'ܡܡܠܠܐ_ܕܣܕܪܐ',
);

$messages = array(
# User preference toggles
'tog-underline'          => 'ܪܫܘܡ ܣܪܛܐ ܬܚܝܬ ܐܣܘܪܐ:',
'tog-justify'            => 'ܫܘܐ ܦܬܓܡ̈ܐ',
'tog-hideminor'          => 'ܛܫܝ ܫܘܚܠܦ̈ܐ ܙܥܘܪ̈ܐ ܒܫܘܚܠܦ̈ܐ ܚܕ̈ܬܐ',
'tog-rememberpassword'   => 'ܕܟܘܪ ܥܠܠܬܝ ܥܠ ܚܫܘܒܬܐ ܗܕܐ',
'tog-watchcreations'     => 'ܐܘܣܦ ܦܐܬܬ̈ܐ ܕܒܪܐ ܐܢܐ ܠܪ̈ܗܝܬܝ',
'tog-watchdefault'       => 'ܐܘܣܦ ܦܐܬܬ̈ܐ ܕܫܚܠܦ ܐܢܐ ܠܪ̈ܗܝܬܝ',
'tog-watchmoves'         => 'ܐܘܣܦ ܦܐܬܬ̈ܐ ܕܫܢܐ ܐܢܐ ܠܪ̈ܗܝܬܝ',
'tog-watchdeletion'      => 'ܐܘܣܦ ܦܐܬܬ̈ܐ ܕܫܦܐ ܐܢܐ ܠܪ̈ܗܝܬܝ',
'tog-watchlisthideown'   => 'ܛܫܝ ܫܘܚܠܦ̈ܝ ܡܢ ܪ̈ܗܝܬܐ',
'tog-watchlisthidebots'  => 'ܛܫܝ ܫܘܚܠܦ̈ܐ ܕܒܘܬ ܡܢ ܪ̈ܗܝܬܐ',
'tog-watchlisthideminor' => 'ܛܫܝ ܫܘܚܠܦ̈ܐ ܙܥܘܪ̈ܐ ܡܢ ܪ̈ܗܝܬܐ',
'tog-watchlisthideliu'   => 'ܛܫܝ ܫܘܚܠܦ̈ܐ ܕܡܦܠܚܢ̈ܐ ܥܠܝܠ̈ܐ ܡܢ ܪ̈ܗܝܬܐ',
'tog-watchlisthideanons' => 'ܛܫܝ ܫܘܚܠܦ̈ܐ ܕܡܦܠܚܢ̈ܐ ܠܐ ܝܕ̈ܝܥܐ ܡܢ ܪ̈ܗܝܬܐ',
'tog-ccmeonemails'       => 'ܫܕܪ ܠܝ ܨܚܚ̈ܐ ܕܒܝܠܕܪ̈ܐ ܐܠܩܛܪ̈ܘܢܝܐ ܕܫܕܪ ܐܢܐ ܠܡܦܠܚܢ̈ܐ ܐܚܪ̈ܢܐ',
'tog-showhiddencats'     => 'ܚܘܝ ܣܕܪ̈ܐ ܛܘܫܝ̈ܐ',

'underline-always' => 'ܐܡܝܢ',
'underline-never'  => 'ܠܐ ܡܡܬܘܡ',

# Dates
'sunday'        => 'ܚܕܒܫܒܐ',
'monday'        => 'ܬܪܝܢܒܫܒܐ',
'tuesday'       => 'ܬܠܬܒܫܒܐ',
'wednesday'     => 'ܐܪܒܥܒܫܒܐ',
'thursday'      => 'ܚܡܫܒܫܒܐ',
'friday'        => 'ܥܪܘܒܬܐ',
'saturday'      => 'ܫܒܬܐ',
'sun'           => 'ܚܕܒܫܒܐ',
'mon'           => 'ܬܪܝܢܒܫܒܐ',
'tue'           => 'ܬܠܬܒܫܒܐ',
'wed'           => 'ܐܪܒܥܒܫܒܐ',
'thu'           => 'ܚܡܫܒܫܒܐ',
'fri'           => 'ܥܪܘܒܬܐ',
'sat'           => 'ܫܒܬܐ',
'january'       => 'ܟܢܘܢ ܒ',
'february'      => 'ܫܒܛ',
'march'         => 'ܐܕܪ',
'april'         => 'ܢܝܣܢ',
'may_long'      => 'ܐܝܪ',
'june'          => 'ܚܙܝܪܢ',
'july'          => 'ܬܡܘܙ',
'august'        => 'ܐܒ',
'september'     => 'ܐܝܠܘܠ',
'october'       => 'ܬܫܪܝܢ ܐ',
'november'      => 'ܬܫܪܝܢ ܒ',
'december'      => 'ܟܢܘܢ ܐ',
'january-gen'   => 'ܟܢܘܢ ܒ',
'february-gen'  => 'ܫܒܛ',
'march-gen'     => 'ܐܕܪ',
'april-gen'     => 'ܢܝܣܢ',
'may-gen'       => 'ܐܝܪ',
'june-gen'      => 'ܚܙܝܪܢ',
'july-gen'      => 'ܬܡܘܙ',
'august-gen'    => 'ܐܒ',
'september-gen' => 'ܐܝܠܘܠ',
'october-gen'   => 'ܬܫܪܝܢ ܐ',
'november-gen'  => 'ܬܫܪܝܢ ܒ',
'december-gen'  => 'ܟܢܘܢ ܐ',
'jan'           => 'ܟܢܘܢ ܒ',
'feb'           => 'ܫܒܛ',
'mar'           => 'ܐܕܪ',
'apr'           => 'ܢܝܣܢ',
'may'           => 'ܐܝܪ',
'jun'           => 'ܚܙܝܪܢ',
'jul'           => 'ܬܡܘܙ',
'aug'           => 'ܐܒ',
'sep'           => 'ܐܝܠܘܠ',
'oct'           => 'ܬܫܪܝܢ ܐ',
'nov'           => 'ܬܫܪܝܢ ܒ',
'dec'           => 'ܟܢܘܢ ܐ',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|ܣܕܪܐ|ܣܕܪ̈ܐ}}',
'category_header'                => 'ܦܐܬܬ̈ܐ ܒܣܕܪܐ ܕ "$1"',
'subcategories'                  => 'ܣܕܪ̈ܐ ܦܪ̈ܥܝܐ',
'category-empty'                 => "''ܗܢܐ ܣܕܪܐ ܗܫܐ ܠܝܬ ܒܗ ܦܐܬܬ̈ܐ ܐܘ ܡܝܕܝܐ.''",
'hidden-categories'              => '{{PLURAL:$1|ܣܕܪܐ ܛܘܫܝܐ|ܣܕܪ̈ܐ ܛܘܫܝܐ}}',
'hidden-category-category'       => 'ܣܕܪ̈ܐ ܛܘܫܝ̈ܐ',
'category-subcat-count-limited'  => 'ܣܕܪܐ ܗܢܐ ܐܝܬ ܒܗ {{PLURAL:$1|ܣܕܪܐ ܦܪܥܝܐ ܗܢܐ|$1 ܣܕܪ̈ܐ ܦܪ̈ܥܝܐ ܗܠܝܢ}}.',
'category-article-count'         => '{{PLURAL:$2|ܣܕܪܐ ܗܢܐ ܐܝܬ ܒܗ ܦܐܬܐ ܗܕܐ ܒܠܚܘܕ.|ܐܝܬ {{PLURAL:$1|ܦܐܬܐ|$1 ܦܐܬܬ̈ܐ}} ܒܣܕܪܐ ܗܢܐ، ܡܢ $2 ܟܠܢܐܝܬ.}}',
'category-article-count-limited' => '{{PLURAL:$1|ܦܐܬܐ ܗܕܐ|$1 ܦܐܬܬ̈ܐ ܗܠܝܢ}} ܒܣܕܪܐ ܗܢܐ.',
'listingcontinuesabbrev'         => '(ܫܘܠܡܐ)',

'about'         => 'ܡܢܘ',
'article'       => 'ܡܓܠܬܐ',
'newwindow'     => '(ܦܬܚ ܒܟܘܬܐ ܚܕܬܐ)',
'cancel'        => 'ܒܛܘܠ',
'moredotdotdot' => '...ܝܬܝܪ',
'mypage'        => 'ܦܐܬܝ',
'mytalk'        => 'ܡܡܠܠܝ',
'anontalk'      => 'ܡܡܠܠܐ ܕܗܢܐ ܐܝ ܦܝ (IP)',
'navigation'    => 'ܐܠܦܪܘܬܐ',
'and'           => '&#32;ܘ',

# Cologne Blue skin
'qbfind'         => 'ܐܫܟܚ',
'qbbrowse'       => 'ܡܦܐܬ',
'qbedit'         => 'ܫܚܠܦ',
'qbpageoptions'  => 'ܗܕܐ ܦܐܬܐ',
'qbmyoptions'    => 'ܓܒܝܬ̈ܝ',
'qbspecialpages' => 'ܦܐܬܬ̈ܐ ܕ̈ܝܠܢܝܬܐ',
'faq'            => 'ܫܘܐܠ̈ܐ ܬܢܝ̈ܐ',
'faqpage'        => 'Project:ܫܘܐܠ̈ܐ ܬܢܝ̈ܐ',

# Vector skin
'vector-action-addsection'   => 'ܐܘܣܦ ܡܠܘܐܐ',
'vector-action-delete'       => 'ܫܘܦ',
'vector-action-move'         => 'ܫܢܝ',
'vector-action-protect'      => 'ܢܛܘܪ',
'vector-action-undelete'     => 'ܠܐ ܫܘܦ',
'vector-action-unprotect'    => 'ܠܐ ܢܛܘܪ',
'vector-namespace-category'  => 'ܣܕܪܐ',
'vector-namespace-help'      => 'ܦܐܬܐ ܕܥܘܕܪܢܐ',
'vector-namespace-image'     => 'ܠܦܦܐ',
'vector-namespace-main'      => 'ܦܐܬܐ',
'vector-namespace-media'     => 'ܦܐܬܐ ܕܡܝܕܝܐ',
'vector-namespace-mediawiki' => 'ܐܓܪܬܐ',
'vector-namespace-project'   => 'ܦܐܬܐ ܕܬܪܡܝܬܐ',
'vector-namespace-special'   => 'ܦܐܬܐ ܕܝܠܢܝܬܐ',
'vector-namespace-talk'      => 'ܕܘܪܫܐ',
'vector-namespace-template'  => 'ܩܠܒܐ',
'vector-namespace-user'      => 'ܦܐܬܐ ܕܡܦܠܚܢܐ',
'vector-view-create'         => 'ܒܪܝ',
'vector-view-edit'           => 'ܫܚܠܦ',
'vector-view-history'        => 'ܚܙܝ ܬܫܥܝܬܐ',
'vector-view-view'           => 'ܩܪܝ',
'vector-view-viewsource'     => 'ܚܙܝ ܥܩܪܐ',
'namespaces'                 => 'ܚܩܠܬ̈ܐ',

'errorpagetitle'    => 'ܦܘܕܐ',
'returnto'          => 'ܕܥܘܪ ܠ$1.',
'tagline'           => 'ܡܢ {{SITENAME}}',
'help'              => 'ܥܘܕܪܢܐ',
'search'            => 'ܒܨܝܐ',
'searchbutton'      => 'ܒܨܝ',
'go'                => 'ܙܠ',
'searcharticle'     => 'ܙܠ',
'history'           => 'ܬܫܥܝܬܐ ܕܦܐܬܐ',
'history_short'     => 'ܬܫܥܝܬܐ',
'info_short'        => 'ܝܕ̈ܥܬܐ',
'printableversion'  => 'ܨܚܚܐ ܡܬܛܒܥܢܐ',
'permalink'         => 'ܐܣܘܪܐ ܦܝܘܫܐ',
'print'             => 'ܛܒܘܥ',
'edit'              => 'ܫܚܠܦ',
'create'            => 'ܒܪܝ',
'editthispage'      => 'ܫܚܠܦ ܦܐܬܐ ܗܕܐ',
'create-this-page'  => 'ܒܪܝ ܦܐܬܐ ܗܕܐ',
'delete'            => 'ܫܘܦ',
'deletethispage'    => 'ܫܘܦ ܦܐܬܐ ܗܕܐ',
'undelete_short'    => 'ܠܐ ܫܘܦ {{PLURAL:$1|ܚܕ ܫܘܚܠܦܐ|$1 ܫܘܚܠܦ̈ܐ}}',
'protect'           => 'ܢܛܘܪ',
'protect_change'    => 'ܫܚܠܦ',
'protectthispage'   => 'ܢܛܘܪ ܗܕܐ ܦܐܬܐ',
'unprotect'         => 'ܠܐ ܢܛܘܪ',
'unprotectthispage' => 'ܠܐ ܢܛܘܪ ܗܕܐ ܦܐܬܐ',
'newpage'           => 'ܦܐܬܐ ܚܕܬܐ',
'talkpage'          => 'ܕܪܘܫ ܗܕܐ ܦܐܬܐ',
'talkpagelinktext'  => 'ܡܡܠܠܐ',
'specialpage'       => 'ܦܐܬܐ ܕܝܠܢܝܬܐ',
'personaltools'     => 'ܡܐܢ̈ܐ ܦܪ̈ܨܘܦܝܐ',
'postcomment'       => 'ܡܢܬܐ ܚܕܬܐ',
'talk'              => 'ܕܘܪܫܐ',
'views'             => 'ܚܙܝܬ̈ܐ',
'toolbox'           => 'ܣܢܕܘܩܐ ܕܡܐܢ̈ܐ',
'userpage'          => 'ܚܙܝ ܦܐܬܐ ܕܡܦܠܚܢܐ',
'projectpage'       => 'ܚܙܝ ܦܐܬܐ ܕܬܪܡܝܬܐ',
'imagepage'         => 'ܚܙܝ ܦܐܬܐ ܕܠܦܦܐ',
'mediawikipage'     => 'ܚܙܝ ܦܐܬܐ ܕܐܓܪܬܐ',
'templatepage'      => 'ܚܙܝ ܦܐܬܐ ܕܩܠܒܐ',
'viewhelppage'      => 'ܚܙܝ ܦܐܬܐ ܕܥܘܕܪܢܐ',
'categorypage'      => 'ܚܙܝ ܦܐܬܐ ܕܣܕܪܐ',
'viewtalkpage'      => 'ܚܙܝ ܕܘܪܫܐ',
'otherlanguages'    => 'ܠܫܢ̈ܐ ܐܚܪ̈ܢܐ',
'redirectedfrom'    => '(ܨܝܒ ܡܢ $1)',
'redirectpagesub'   => 'ܦܐܬܐ ܕܨܘܝܒܐ',
'lastmodifiedat'    => 'ܫܘܚܠܦܐ ܐܚܪܝܐ ܕܦܐܬܐ ܗܕܐ ܗܘܐ ܒܣܝܩܘܡ $1, $2.',
'protectedpage'     => 'ܦܐܬܐ ܢܛܝܪܬܐ',
'jumpto'            => 'ܫܘܪ ܠ:',
'jumptonavigation'  => 'ܐܠܦܪܘܬܐ',
'jumptosearch'      => 'ܒܨܝܐ',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'ܡܢܘ {{SITENAME}}',
'aboutpage'            => 'Project:ܡܢܘ',
'copyright'            => 'ܚܒܝܫܬ̈ܐ ܐܝܬ ܬܚܝܬ $1.',
'copyrightpage'        => '{{ns:project}}:ܙܕܩ̈ܐ ܕܚܬܡܐ',
'currentevents'        => 'ܓܕ̈ܫܐ ܗܫܝ̈ܐ',
'currentevents-url'    => 'Project:ܓܕ̈ܫܐ ܗܫܝ̈ܐ',
'disclaimers'          => 'ܠܐ ܡܫܬܐܠܢܘܬܐ',
'disclaimerpage'       => 'Project:ܠܐ ܡܫܬܐܠܢܘܬܐ ܓܘܢܝܬܐ',
'edithelp'             => 'ܥܘܕܪܢܐ ܠܫܚܠܦܬܐ',
'edithelppage'         => 'Help:ܫܚܠܦܬܐ',
'helppage'             => 'Help:ܚܒܝܫܬ̈ܐ',
'mainpage'             => 'ܦܐܬܐ ܪܫܝܬܐ',
'mainpage-description' => 'ܦܐܬܐ ܪܫܝܬܐ',
'portal'               => 'ܬܪܥܐ ܕܟܢܫܐ',
'portal-url'           => 'Project:ܬܪܥܐ ܕܟܢܫܐ',

'badaccess' => 'ܦܘܕܐ ܒܦܣܣܐ',

'ok'                      => 'ܛܒ',
'youhavenewmessages'      => 'ܐܝܬ ܠܟ $1 ($2).',
'newmessageslink'         => 'ܐܓܪ̈ܬܐ ܚܕ̈ܬܬܐ',
'newmessagesdifflink'     => 'ܫܘܚܠܦܐ ܐܚܪܝܐ',
'youhavenewmessagesmulti' => 'ܐܝܬ ܠܟ ܐܓܪ̈ܬܐ ܚܕ̈ܬܬܐ ܒ $1',
'editsection'             => 'ܫܚܠܦ',
'editold'                 => 'ܫܚܠܦ',
'viewsourceold'           => 'ܚܙܝ ܥܩܪܐ',
'editlink'                => 'ܫܚܠܦ',
'viewsourcelink'          => 'ܚܙܝ ܥܩܪܐ',
'editsectionhint'         => 'ܫܚܠܦ ܡܢܬܐ: $1',
'toc'                     => 'ܚܒܝܫܬ̈ܐ',
'showtoc'                 => 'ܚܘܝ',
'hidetoc'                 => 'ܛܫܝ',
'viewdeleted'             => 'ܚܙܝ $1?',
'restorelink'             => '{{PLURAL:$1|ܚܕ ܫܘܚܠܦܐ ܫܝܦܐ|$1 ܫܘܚܠܦ̈ܐ ܫܝܦ̈ܐ}}',
'red-link-title'          => '$1 (ܦܐܬܐ ܗܕܐ ܠܝܬ)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'ܦܐܬܐ',
'nstab-user'      => 'ܦܐܬܐ ܕܡܦܠܚܢܐ',
'nstab-media'     => 'ܦܐܬܐ ܕܡܝܕܝܐ',
'nstab-special'   => 'ܦܐܬܐ ܕܝܠܢܝܬܐ',
'nstab-project'   => 'ܦܐܬܐ ܕܬܪܡܝܬܐ',
'nstab-image'     => 'ܠܦܦܐ',
'nstab-mediawiki' => 'ܐܓܪܬܐ',
'nstab-template'  => 'ܩܠܒܐ',
'nstab-help'      => 'ܦܐܬܐ ܕܥܘܕܪܢܐ',
'nstab-category'  => 'ܣܕܪܐ',

# Main script and global functions
'nosuchaction'      => 'ܠܝܬ ܗܟܘܬ ܥܒܕܐ',
'nosuchspecialpage' => 'ܠܝܬ ܗܟܘܬ ܦܐܬܐ ܕܝܠܢܝܬܐ',

# General errors
'error'               => 'ܦܘܕܐ',
'missingarticle-rev'  => '(ܬܢܝܬܐ#: $1)',
'missingarticle-diff' => '(ܦܪܝܫܘܬܐ: $1, $2)',
'internalerror'       => 'ܦܘܕܐ ܓܘܝܐ',
'internalerror_info'  => 'ܦܘܕܐ ܓܘܝܐ: $1',
'viewsource'          => 'ܚܙܝ ܥܩܪܐ',
'viewsourcefor'       => 'ܕ $1',
'namespaceprotected'  => "ܠܝܬ ܠܟ ܦܣܣܐ ܠܫܚܠܦܬܐ ܕܦܐܬܬ̈ܐ ܒܚܩܠܐ ܕ'''$1'''.",

# Login and logout pages
'logouttext'              => "'''ܗܫܐ ܦܠܛܠܟ ܡܢ ܚܘܫܒܢܟ.'''

ܡܨܐ ܐܢܬ ܕܐܬܚܫܚ {{SITENAME}} ܐܝܟ ܡܬܚܫܚܢܐ ܠܐ ܝܕܝܥܐ. ܐܘ ܡܨܐ ܐܢܬ ܕ[[Special:UserLogin|ܬܥܘܠ]] ܒܚܘܫܒܢܐ ܥܝܢܗ ܐܘ ܐܝܟ ܡܬܚܫܚܢܐ ܐܚܪܢܐ.
Note that some pages may continue to be displayed as if you were still logged in, until you clear your browser cache.",
'welcomecreation'         => '== ܒܫܝܢܐ, $1! ==
ܐܬܒܪܝ ܚܘܫܒܢܟ.
ܠܐ ܢܫܐ ܐܢܬ ܠܫܚܠܦܬܐ ܕ[[Special:Preferences|ܓܒܝܬ̈ܐ ܒ {{SITENAME}}]].',
'yourname'                => 'ܫܡܐ ܕܡܦܠܚܢܐ:',
'yourpassword'            => 'ܡܠܬܐ ܕܥܠܠܐ:',
'yourpasswordagain'       => 'ܟܬܘܒ ܡܠܬܐ ܕܥܠܠܐ ܙܒܢܬܐ ܐܚܪܬܐ:',
'remembermypassword'      => 'ܕܟܘܪ ܥܠܠܬܝ ܥܠ ܚܫܘܒܬܐ ܗܕܐ',
'login'                   => 'ܥܘܠ',
'nav-login-createaccount' => 'ܥܘܠ / ܒܪܝ ܚܘܫܒܢܐ',
'userlogin'               => 'ܥܘܠ / ܒܪܝ ܚܘܫܒܢܐ',
'logout'                  => 'ܦܠܛܐ',
'userlogout'              => 'ܦܠܘܛ',
'notloggedin'             => 'ܠܐ ܥܠܝܠܐ',
'nologin'                 => "ܠܝܬ ܠܟ ܚܘܫܒܢܐ؟ '''$1'''.",
'nologinlink'             => 'ܒܪܝ ܚܘܫܒܢܐ',
'createaccount'           => 'ܒܪܝ ܚܘܫܒܢܐ',
'gotaccount'              => "ܐܝܬ ܠܟ ܚܘܫܒܢܐ؟ '''$1'''.",
'gotaccountlink'          => 'ܥܘܠ',
'createaccountmail'       => 'ܒܝܕ ܒܝܠܕܪܐ ܐܠܩܛܪܘܢܝܐ',
'badretype'               => 'ܡܠܬܐ ܕܥܠܠܐ ܟܬܒܬ ܐܢܬ ܠܐ ܐܘܝܢܬܐ.',
'userexists'              => 'ܫܡܐ ܕܡܦܠܚܢܐ ܫܩܝܠܐ.
ܓܒܝ ܫܡܐ ܐܚܪܢܐ.',
'loginerror'              => 'ܦܘܕܐ ܒܥܠܠܐ',
'loginsuccesstitle'       => 'ܥܠܠܐ ܓܡܪ',
'loginsuccess'            => "'''ܗܫܐ ܥܠܝܠܐ ܐܢܬ ܒ {{SITENAME}} ܐܝܟ \"\$1\".'''",
'mailmypassword'          => 'ܫܕܪ ܠܝ ܡܠܬܐ ܚܕܬܐ ܕܥܠܠܐ',
'passwordremindertitle'   => 'ܡܠܬܐ ܕܥܠܠܐ ܙܒܢܢܝܬܐ ܚܕܬܐ ܠ{{SITENAME}}',
'noemail'                 => 'ܠܝܬ ܒܝܠܕܪܐ ܐܠܩܛܪܘܢܝܐ ܠܡܦܠܚܢܐ "$1".',
'emailconfirmlink'        => 'ܚܬܬ ܒܝܠܕܪܐ ܐܠܩܛܪܘܢܝܐ ܕܝܠܟ',
'accountcreated'          => 'ܚܘܫܒܢܐ ܒܪܐ',
'accountcreatedtext'      => 'ܐܬܒܪܝ ܚܘܫܒܢܐ ܕܡܦܠܚܢܐ ܠ $1.',
'createaccount-title'     => 'ܒܪܝܐ ܕܚܘܫܒܢܐ ܒ {{SITENAME}}',
'loginlanguagelabel'      => 'ܠܫܢܐ: $1',

# Password reset dialog
'resetpass'                 => 'ܫܚܠܦ ܡܠܬܐ ܕܥܠܠܐ',
'resetpass_header'          => 'ܫܚܠܦ ܡܠܬܐ ܕܥܠܠܐ ܕܚܘܫܒܢܐ',
'oldpassword'               => 'ܡܠܬܐ ܕܥܠܠܐ ܥܬܝܩܬܐ:',
'newpassword'               => 'ܡܠܬܐ ܕܥܠܠܐ ܚܕܬܐ:',
'retypenew'                 => 'ܟܬܘܒ ܡܠܬܐ ܕܥܠܠܐ ܙܒܢܬܐ ܐܚܪܬܐ:',
'resetpass-submit-loggedin' => 'ܫܚܠܦ ܡܠܬܐ ܕܥܠܠܐ',
'resetpass-temp-password'   => 'ܡܠܬܐ ܕܥܠܠܐ ܙܒܢܢܝܬܐ:',

# Edit page toolbar
'bold_sample'   => 'ܟܬܒܬܐ ܥܒܝܬܐ',
'bold_tip'      => 'ܟܬܒܬܐ ܥܒܝܬܐ',
'italic_sample' => 'ܟܬܒܬܐ ܦܠܝܡܬܐ',
'italic_tip'    => 'ܟܬܒܬܐ ܦܠܝܡܬܐ',
'link_tip'      => 'ܐܣܘܪܐ ܓܘܝܐ',
'extlink_tip'   => 'ܐܣܘܪܐ ܒܪܝܐ (ܕܟܘܪ http:// ܩܕܡܝܬܐ)',
'media_tip'     => 'ܐܣܘܪܐ ܕܠܦܦܐ',
'sig_tip'       => 'ܪܡܝ ܐܝܕܟ ܥܡ ܙܒܢܐ ܘܣܝܩܘܡܐ',

# Edit pages
'summary'                          => 'ܦܣܝܩܬ̈ܐ ܕܫܘܚܠܦܐ:',
'minoredit'                        => 'ܗܢܐ ܗܘ ܫܘܚܠܦܐ ܙܥܘܪܐ',
'watchthis'                        => 'ܪܗܝ ܦܐܬܐ ܗܕܐ',
'savearticle'                      => 'ܠܒܘܟ ܕܦܐ',
'preview'                          => 'ܚܝܪܐ ܩܕܡܝܐ',
'showpreview'                      => 'ܚܘܝ ܚܝܪܐ ܩܕܡܝܐ',
'showdiff'                         => 'ܚܘܝ ܫܘܚܠܦ̈ܐ',
'anoneditwarning'                  => "'''ܙܘܗܪܐ:''' ܠܐ ܥܠܝܠܐ ܐܢܬ.
ܐܝ ܦܝ (IP) ܕܝܠܟ ܢܬܟܬܒ ܒܬܫܥܝܬܐ ܕܦܐܬܐ.",
'summary-preview'                  => 'ܚܝܪܐ ܩܕܡܝܐ ܕܦܣܝܩܬ̈ܐ :',
'blockedtitle'                     => 'ܡܦܠܚܢܐ ܗܘ ܡܚܪܡܐ',
'nosuchsectiontitle'               => 'ܠܝܬ ܗܟܘܬ ܡܢܬܐ',
'loginreqlink'                     => 'ܥܘܠ',
'newarticle'                       => '(ܚܕܬܐ)',
'updated'                          => '(ܐܬܚܕܬ)',
'note'                             => "'''ܡܥܝܪܢܘܬܐ:'''",
'previewnote'                      => "'''ܕܟܘܪ ܗܢܘ ܚܝܪܐ ܩܕܡܝܐ ܒܠܚܘܕ''' ܫܘܚܠܦ̈ܐ ܕܝܠܟ ܠܐ ܐܬܠܒܟܬ ܥܕܡܐ ܠܗܫܐ",
'editing'                          => 'ܫܚܠܦܬܐ ܕ $1',
'editingsection'                   => 'ܫܚܠܦܬܐ ܕ $1 (ܡܢܬܐ)',
'editingcomment'                   => 'ܫܚܠܦܬܐ ܕ $1 (ܡܢܬܐ ܚܕܬܐ)',
'yourtext'                         => 'ܟܬܒܬܐ ܕܝܠܟ',
'yourdiff'                         => 'ܦܪ̈ܝܫܘܝܬܐ',
'templatesused'                    => '{{PLURAL:$1|ܩܠܒܐ|ܩܠܒ̈ܐ}} ܒܦܐܬܐ ܗܕܐ:',
'template-protected'               => '(ܢܛܝܪܐ)',
'template-semiprotected'           => '(ܕܡܘܬ ܢܛܝܪܐ)',
'nocreate-loggedin'                => 'ܠܝܬ ܠܟ ܦܣܣܐ ܕܒܪܝܐ ܕܦܐܬܐ ܗܕܐ.',
'permissionserrors'                => 'ܦܘܕ̈ܐ ܕܦܣܣ̈ܐ',
'permissionserrorstext-withaction' => 'ܠܝܬ ܠܟ ܦܣܣܐ ܠ$2, ܒ{{PLURAL:$1|ܥܠܬܐ|ܥܠܬ̈ܐ}} ܕ:',
'log-fulllog'                      => 'ܚܙܝ ܣܓܠܐ ܓܡܝܪܐ',
'edit-already-exists'              => 'ܒܪܝܐ ܕܦܐܬܐ ܚܕܬܐ ܠܐ ܡܬܡܨܝܢܐ.
ܦܐܬܐ ܐܝܬ ܡܢ ܟܕܘ.',

# "Undo" feature
'undo-summary' => 'ܠܐ ܥܒܘܕ $1 ܒܝܕ [[Special:Contributions/$2|$2]] ([[User talk:$2|ܕܘܪܫܐ]])',

# Account creation failure
'cantcreateaccounttitle' => 'ܒܪܝܐ ܕܚܘܫܒܢܐ ܠܐ ܡܬܡܨܝܢܐ',

# History pages
'viewpagelogs'           => 'ܚܙܝ ܣܓܠ̈ܐ ܕܦܐܬܐ ܗܕܐ',
'nohistory'              => 'ܠܝܬ ܬܫܥܝܬܐ ܕܫܘܚܠܦ̈ܐ ܠܦܐܬܐ ܗܕܐ',
'currentrev'             => 'ܬܢܝܬܐ ܗܫܝܬܐ',
'currentrev-asof'        => 'ܬܢܝܬܐ ܗܫܝܬܐ ܒܣܝܩܘܡ $1',
'revisionasof'           => 'ܬܢܝܬܐ ܒܣܝܩܘܡ $1',
'revision-info'          => 'ܬܢܝܬܐ ܒܣܝܩܘܡ $1 ܒܝܕ $2',
'previousrevision'       => '← ܬܢܝܬܐ ܥܬܝܩܬܐ',
'nextrevision'           => 'ܬܢܝܬܐ ܚܕܬܐ →',
'currentrevisionlink'    => 'ܬܢܝܬܐ ܗܫܝܬܐ',
'cur'                    => 'ܗܫܝܐ',
'next'                   => 'ܒܬܪ',
'last'                   => 'ܩܕܡ',
'page_first'             => 'ܩܕܡܝܐ',
'page_last'              => 'ܐܚܪܝܐ',
'histlegend'             => "ܓܒܝܐ ܕܦܪܝܫܘܬܐ: ܓܒܝ ܣܢܕ̈ܘܩܐ ܕܬܢܝܬ̈ܐ ܠܦܘܚܡܐ ܘܕܘܫ '''Enter''' ܐܘ '''ܦܚܘܡ ܒܝܬ ܬܪܝܢ ܬܢܝܬ̈ܐ ܓܒܝܬ̈ܐ'''.<br />
ܩܠܝܕܐ: '''({{int:cur}})''' = ܦܪܝܫܘܬܐ ܥܡ ܬܢܝܬܐ ܗܫܝܬܐ,
'''({{int:last}})''' = ܦܪܝܫܘܬܐ ܥܡ ܬܢܝܬܐ ܩܕܝܡܬܐ, '''{{int:minoreditletter}}''' = ܫܘܚܠܦܐ ܙܥܘܪܐ.",
'history-fieldset-title' => 'ܡܦܐܬ ܬܫܥܝܬܐ',
'history-show-deleted'   => 'ܫܝܦܬ̈ܐ ܒܠܚܘܕ',
'histfirst'              => 'ܩܕܝܡ ܟܠ',
'histlast'               => 'ܐܚܪܝ ܟܠ',
'historyempty'           => '(ܣܦܝܩܐ)',

# Revision feed
'history-feed-title'          => 'ܬܫܥܝܬܐ ܕܬܢܝܬܐ',
'history-feed-description'    => 'ܬܫܥܝܬܐ ܕܬܢܝܬܐ ܕܗܕܐ ܦܐܬܐ ܥܠ ܘܝܩܝ',
'history-feed-item-nocomment' => '$1 ܒ $2',

# Revision deletion
'rev-delundel'               => 'ܚܘܝ/ܛܫܝ',
'revisiondelete'             => 'ܫܘܦ/ܠܐ ܫܘܦ ܬܢܝܬ̈ܐ',
'revdelete-show-file-submit' => 'ܐܝܢ',
'revdelete-selected'         => "'''{{PLURAL:$2|ܬܢܝܬܐ ܓܒܝܬܐ|ܬܢܝܬ̈ܐ ܓܒܝܬܐ}} ܕ [[:$1]]:'''",
'revdelete-hide-text'        => 'ܛܫܝ ܟܬܒܬܐ ܕܬܢܝܬܐ',
'revdelete-hide-user'        => 'ܛܫܝ ܫܡܐ/ܐܝ ܦܝ (IP) ܕܡܦܠܚܢܐ',
'revdelete-hide-image'       => 'ܛܫܝ ܚܒܝܫܬ̈ܐ ܕܠܦܦܐ',
'revdel-restore'             => 'ܫܚܠܦ ܚܙܝܬܐ',
'pagehist'                   => 'ܬܫܥܝܬܐ ܕܦܐܬܐ',
'deletedhist'                => 'ܬܫܥܝܬܐ ܫܝܦܬܐ',
'revdelete-summary'          => 'ܫܚܠܦ ܦܣܝܩܬ̈ܐ',
'revdelete-uname'            => 'ܫܡܐ ܕܡܦܠܚܢܐ',
'revdelete-log-message'      => '$1 ܠ $2 {{PLURAL:$2|ܬܢܝܬܐ|ܬܢܝܬ̈ܐ}}',
'logdelete-log-message'      => '$1 ܠ $2 {{PLURAL:$2|ܓܕܫܐ|ܓܕ̈ܫܐ}}',
'revdelete-edit-reasonlist'  => 'ܫܚܠܦ ܥܠܠܬ̈ܐ ܕܫܝܦܐ',

# History merging
'mergehistory'             => 'ܚܒܘܛ ܬܫܥܝܬ̈ܐ ܕܦܐܬܐ',
'mergehistory-box'         => 'ܚܒܘܛ ܬܢܝܬ̈ܐ ܕܬܪܬܝܢ ܦܐܬܬ̈ܐ',
'mergehistory-from'        => 'ܦܐܬܐ ܕܥܩܪܐ:',
'mergehistory-submit'      => 'ܚܒܘܛ ܬܢܝܬ̈ܐ',
'mergehistory-no-source'   => 'ܦܐܬܐ ܕܥܩܪܐ $1 ܠܝܬ.',
'mergehistory-autocomment' => 'ܚܒܛ [[:$1]] ܒ [[:$2]]',
'mergehistory-comment'     => 'ܚܒܛ [[:$1]] ܒ [[:$2]]: $3',
'mergehistory-reason'      => 'ܥܠܬܐ:',

# Merge log
'mergelog'           => 'ܣܓܠܐ ܕܚܒܛܐ',
'pagemerge-logentry' => 'ܚܒܛ [[$1]] ܒ [[$2]] (ܬܢܝܬ̈ܐ ܥܕܡܐ ܠ $3)',
'revertmerge'        => 'ܒܛܘܠ ܚܒܛܐ',

# Diffs
'history-title'            => 'ܬܫܥܝܬܐ ܕܬܢܝܬܐ ܕ "$1"',
'difference'               => '(ܦܪܝܫܘܬܐ ܒܝܬ ܬܢܝܬ̈ܐ)',
'lineno'                   => 'ܣܪܛܐ $1:',
'compareselectedversions'  => 'ܦܚܘܡ ܒܝܬ ܬܪܝܢ ܬܢܝܬ̈ܐ ܓܒܝܬ̈ܐ',
'showhideselectedversions' => 'ܚܘܝ/ܛܫܝ ܬܢܝܬ̈ܐ ܓܒܝܬ̈ܐ',
'editundo'                 => 'ܠܐ ܥܒܘܕ',

# Search results
'searchresults'                  => 'ܦܠܛ̈ܐ ܕܒܘܨܝܐ',
'searchresults-title'            => 'ܦܠܛ̈ܐ ܕܒܘܨܝܐ ܥܠ "$1"',
'searchresulttext'               => 'ܠܝܬܝܪ ܝܕ̈ܥܬܐ ܥܠ ܒܨܝܐ ܒ {{SITENAME}}, ܚܙܝ [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                 => 'ܒܨܐ ܐܢܬ ܥܠ \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|ܟܠ ܦܐܬܬ̈ܐ ܕܫܪܝܢ ܒ"$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|ܟܠ ܦܐܬܬ̈ܐ ܕܐܣܪܝܢ ܥܡ "$1"]])',
'searchsubtitleinvalid'          => "ܒܨܐ ܐܢܬ ܥܠ '''$1'''",
'titlematches'                   => 'ܫܡܐ ܕܦܐܬܐ ܐܘܝܢܐ',
'notitlematches'                 => 'ܠܝܬ ܫܡܐ ܕܦܐܬܐ ܐܘܝܢܐ',
'textmatches'                    => 'ܟܬܒܬܐ ܐܘܝܢܬܐ',
'notextmatches'                  => 'ܠܝܬ ܟܬܒܬܐ ܐܘܝܢܬܐ',
'prevn'                          => '{{PLURAL:$1|$1}} ܩܕܝܡܐ',
'nextn'                          => '{{PLURAL:$1|$1}} ܐܚܪܢܐ',
'shown-title'                    => 'ܚܘܝ $1 {{PLURAL:$1|ܦܠܛܐ|ܦܠܛ̈ܐ}} ܠܟܠ ܦܐܬܐ',
'viewprevnext'                   => 'ܚܘܝ ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'              => 'ܓܒܝܬ̈ܐ ܕܒܘܨܝܐ',
'searchmenu-exists'              => "'''ܐܝܬ ܦܐܬܐ ܒܫܡ \"[[:\$1]]\" ܥܠ ܗܢܐ ܘܝܩܝ'''",
'searchmenu-new'                 => "'''ܒܪܝ ܦܐܬܐ \"[[:\$1]]\" ܥܠ ܗܢܐ ܘܝܩܝ!'''",
'searchhelp-url'                 => 'Help:ܚܒܝܫܬ̈ܐ',
'searchprofile-articles'         => 'ܦܐܬܬ̈ܐ ܕܚܒܝܫܬ̈ܐ',
'searchprofile-project'          => 'ܦܐܬܬ̈ܐ ܕܬܪ̈ܡܝܬܐ ܘܕܥܘܕܪܢܐ',
'searchprofile-images'           => 'ܡܝܕ̈ܝܐ ܣܓܝܐ̈ܐ (Multimedia)',
'searchprofile-everything'       => 'ܟܠ ܡܕܡ',
'searchprofile-advanced'         => 'ܡܬܩܕܡܢܐ',
'searchprofile-articles-tooltip' => 'Search in $1',
'searchprofile-project-tooltip'  => 'Search in $1',
'search-result-size'             => '$1 ({{PLURAL:$2|1 ܡܠܬܐ|$2 ܡܠ̈ܐ}})',
'search-redirect'                => '(ܨܝܒ $1)',
'search-section'                 => '(ܡܢܬܐ $1)',
'search-suggest'                 => 'ܣܟܠ ܐܢܬ: $1',
'search-interwiki-default'       => 'ܦܠܛ̈ܐ ܕ $1:',
'search-interwiki-more'          => '(ܝܬܝܪ)',
'search-mwsuggest-enabled'       => 'ܥܡ ܡܚܫܚܬ̈ܐ',
'search-mwsuggest-disabled'      => 'ܠܐ ܡܚܫܚܬ̈ܐ',
'searcheverything-enable'        => 'ܒܨܝ ܒܟܠ ܚܩܠܬ̈ܐ',
'searchall'                      => 'ܟܠ',
'showingresults'                 => "ܚܘܘܝܐ ܠܬܚܬ {{PLURAL:$1|'''1''' ܦܠܛܐ|'''$1''' ܦܠܛ̈ܐ}} ܫܪܐ ܡܢ ܡܢܝܢܐ '''$2'''.",
'showingresultsheader'           => "{{PLURAL:$5|ܦܠܛܐ '''$1''' ܡܢ '''$3'''|ܦܠܛ̈ܐ '''$1 - $2''' ܡܢ '''$3'''}} ܠ'''$4'''",
'search-nonefound'               => 'ܠܝܬ ܦܠܛ̈ܐ ܐܘܝ ܠܗܢܐ ܫܘܐܠܐ.',
'powersearch'                    => 'ܒܨܝܐ ܡܬܩܕܡܢܐ',
'powersearch-legend'             => 'ܒܨܝܐ ܡܬܩܕܡܢܐ',
'powersearch-ns'                 => 'ܒܨܝ ܒܚܩܠܬ̈ܐ:',
'powersearch-redir'              => 'ܚܘܝ ܨܘܝܒ̈ܐ',
'powersearch-field'              => 'ܒܨܝ ܥܠ',
'powersearch-toggleall'          => 'ܟܠ',
'powersearch-togglenone'         => 'ܠܐ ܡܕܡ',
'search-external'                => 'ܒܘܨܝܐ ܒܪܝܐ',

# Quickbar
'qbsettings-none' => 'ܠܐ ܡܕܡ',

# Preferences page
'preferences'                 => 'ܓܒܝܬ̈ܐ',
'mypreferences'               => 'ܓܒܝܬ̈ܝ',
'prefs-edits'                 => 'ܡܢܝܢܐ ܕܫܘܚܠܦ̈ܐ:',
'prefsnologin'                => 'ܠܐ ܥܠܝܠܐ',
'changepassword'              => 'ܫܚܠܦ ܡܠܬܐ ܕܥܠܠܐ',
'prefs-skin'                  => 'ܓܠܕܐ',
'skin-preview'                => 'ܚܝܪܐ ܩܕܡܝܐ',
'prefs-math'                  => 'ܡܬܡܐܛܝܩܘܬܐ',
'datedefault'                 => 'ܠܐ ܓܒܝܬܐ',
'prefs-datetime'              => 'ܣܝܩܘܡܐ ܘܙܒܢܐ',
'prefs-personal'              => 'ܠܦܦܐ ܕܡܦܠܚܢܐ',
'prefs-rc'                    => 'ܫܘܚܠܦ̈ܐ ܚܕ̈ܬܐ',
'prefs-watchlist'             => 'ܪ̈ܗܝܬܐ',
'prefs-watchlist-days'        => 'ܝܘܡܬ̈ܐ ܠܚܙܝܐ ܒܪ̈ܗܝܬܐ:',
'prefs-resetpass'             => 'ܫܚܠܦ ܡܠܬܐ ܕܥܠܠܐ',
'prefs-email'                 => 'ܓܒܝܬ̈ܐ ܕܒܝܠܕܪܐ ܐܠܩܛܪܘܢܝܐ',
'prefs-rendering'             => 'ܐܣܟܝܡܐ',
'saveprefs'                   => 'ܠܒܘܟ',
'resetprefs'                  => 'ܡܫܝ ܫܘܚܠܦ̈ܐ ܠܐ ܠܒܝܟ̈ܐ',
'prefs-editing'               => 'ܫܚܠܦܬܐ',
'columns'                     => 'ܥܡܘܕ̈ܐ:',
'searchresultshead'           => 'ܒܨܝ',
'recentchangesdays'           => 'ܝܘܡܬ̈ܐ ܠܚܙܝܐ ܒܫܘܚܠܦ̈ܐ ܚܕ̈ܬܐ:',
'recentchangescount'          => 'ܡܢܝܢܐ ܕܫܘܚܠܦ̈ܐ ܠܚܙܝܐ ܪܫܐܝܬ:',
'savedprefs'                  => 'ܓܒܝܬ̈ܐ ܕܝܠܟ ܐܬܠܒܟܬ.',
'prefs-searchoptions'         => 'ܓܒܝܬ̈ܐ ܕܒܘܨܝܐ',
'prefs-namespaces'            => 'ܚܩܠܬ̈ܐ',
'defaultns'                   => 'ܐܘ ܒܨܝ ܒܚܩܠܬ̈ܐ ܗܢܝܢ',
'prefs-files'                 => 'ܠܦܦ̈ܐ',
'prefs-emailconfirm-label'    => 'ܚܘܬܬܐ ܕܒܝܠܕܪܐ ܐܠܩܛܪܘܢܝܐ:',
'youremail'                   => 'ܒܝܠܕܪܐ ܐܠܩܛܪܘܢܝܐ:',
'username'                    => 'ܫܡܐ ܕܡܦܠܚܢܐ:',
'uid'                         => 'ܗܝܝܘܬܐ ܕܡܦܠܚܢܐ:',
'prefs-memberingroups'        => 'ܗܕܡܐ ܕ {{PLURAL:$1|ܟܢܘܫܬܐ|ܟܢܘܫܬ̈ܐ}}:',
'prefs-registration'          => 'ܙܒܢܐ ܕܣܘܓܠܐ:',
'yourrealname'                => 'ܫܡܐ ܫܪܝܪܐ:',
'yourlanguage'                => 'ܠܫܢܐ:',
'yournick'                    => 'ܪܡܝ ܐܝܕܐ:',
'badsiglength'                => 'ܪܡܝ ܐܝܕܟ ܣܓܝ ܐܪܝܟܬܐ.
ܐܠܨܐ ܠܟ ܠܐ ܝܬܝܪ ܡܢ $1 {{PLURAL:$1|ܐܬܘܬܐ|ܐܬܘܬ̈ܐ}} ܐܪܝܟܬܐ ܗܘܬ.',
'yourgender'                  => 'ܓܢܣܐ:',
'gender-unknown'              => 'ܠܐ ܦܣܝܩܐ',
'gender-male'                 => 'ܕܟܪܐ',
'gender-female'               => 'ܢܩܒܐ',
'email'                       => 'ܒܝܠܕܪܐ ܐܠܩܛܪܘܢܝܐ',
'prefs-info'                  => 'ܝܕ̈ܥܬܐ ܪ̈ܫܝܬܐ',
'prefs-i18n'                  => 'ܬܘܪܓܡܐ',
'prefs-signature'             => 'ܪܡܝ ܐܝܕܐ',
'prefs-dateformat'            => 'ܚܫܠܬܐ ܕܙܒܢܐ',
'prefs-advancedediting'       => 'ܓܒܝܬ̈ܐ ܡܬܩܕ̈ܡܢܐ',
'prefs-advancedrc'            => 'ܓܒܝܬ̈ܐ ܡܬܩܕ̈ܡܢܐ',
'prefs-advancedrendering'     => 'ܓܒܝܬ̈ܐ ܡܬܩܕ̈ܡܢܐ',
'prefs-advancedsearchoptions' => 'ܓܒܝܬ̈ܐ ܡܬܩܕ̈ܡܢܐ',
'prefs-advancedwatchlist'     => 'ܓܒܝܬ̈ܐ ܡܬܩܕ̈ܡܢܐ',
'prefs-display'               => 'ܚܘܝ ܓܒܝܬ̈ܐ',
'prefs-diffs'                 => 'ܦܪ̈ܝܫܘܝܬܐ',

# User rights
'userrights'               => 'ܡܕܒܪܢܘܬܐ ܕܙܕ̈ܩܐ ܕܡܦܠܚܢܐ',
'userrights-lookup-user'   => 'ܕܒܪ ܟܢܘܫܝ̈ܐ ܕܡܦܠܚܢܐ',
'userrights-user-editname' => 'ܐܥܠ ܫܡܐ ܕܡܦܠܚܢܐ:',
'editusergroup'            => 'ܫܚܠܦ ܟܢܘܫܝ̈ܐ ܕܡܦܠܚܢܐ',
'userrights-editusergroup' => 'ܫܚܠܦ ܟܢܘܫܝ̈ܐ ܕܡܦܠܚܢܐ',
'saveusergroups'           => 'ܠܒܘܟ ܟܢܘܫܝ̈ܐ ܕܡܦܠܚܢܐ',
'userrights-groupsmember'  => 'ܗܕܡܐ ܒ:',
'userrights-reason'        => 'ܥܠܬܐ ܠܫܚܠܦܬܐ:',

# Groups
'group'            => 'ܟܢܘܫܬܐ:',
'group-user'       => 'ܡܦܠܚܢ̈ܐ',
'group-bot'        => 'ܒܘܬ̈ܐ',
'group-sysop'      => 'ܡܕܒܪ̈ܢܐ',
'group-bureaucrat' => 'ܒܝܪܘܩܪ̈ܛܐ',
'group-suppress'   => 'ܚܝܘܪ̈ܐ',
'group-all'        => '(ܟܠ)',

'group-user-member'       => 'ܡܦܠܚܢܐ',
'group-bot-member'        => 'ܒܘܬ (Bot)',
'group-sysop-member'      => 'ܡܕܒܪܢܐ',
'group-bureaucrat-member' => 'ܒܝܪܘܩܪܛܐ',
'group-suppress-member'   => 'ܚܝܘܪܐ',

'grouppage-user'       => '{{ns:project}}:ܡܦܠܚܢ̈ܐ',
'grouppage-bot'        => '{{ns:project}}:ܒܘܬ̈ܐ',
'grouppage-sysop'      => '{{ns:project}}:ܡܕܒܪ̈ܢܐ',
'grouppage-bureaucrat' => '{{ns:project}}:ܒܝܪܘܩܪ̈ܛܐ',
'grouppage-suppress'   => '{{ns:project}}:ܚܝܘܪܐ',

# Rights
'right-read'           => 'ܩܪܝ ܦܐܬܬ̈ܐ',
'right-edit'           => 'ܫܚܠܦ ܦܐܬܬ̈ܐ',
'right-createtalk'     => 'ܒܪܝ ܦܐܬܬ̈ܐ ܕܕܘܪܫܐ',
'right-createaccount'  => 'ܒܪܝ ܚܘܫܒܢ̈ܐ ܕܡܦܠܚܢܐ ܚܕܬܐ',
'right-minoredit'      => 'ܫܘܕܥ ܥܠ ܫܘܚܠܦ̈ܐ ܐܝܟ ܙܥܘܪܐ',
'right-move'           => 'ܫܢܝ ܦܐܬܬ̈ܐ',
'right-movefile'       => 'ܫܢܝ ܠܦܦ̈ܐ',
'right-upload'         => 'ܐܣܩ ܠܦܦ̈ܐ',
'right-delete'         => 'ܫܘܦ ܦܐܬܬ̈ܐ',
'right-bigdelete'      => 'ܫܘܦ ܦܐܬܬ̈ܐ ܥܡ ܬܫܥܝܬ̈ܐ ܪ̈ܒܬܐ',
'right-browsearchive'  => 'ܒܨܝ ܦܐܬܬ̈ܐ ܫܝܦܬ̈ܐ',
'right-undelete'       => 'ܠܐ ܫܘܦ ܦܐܬܬ̈ܐ',
'right-suppressionlog' => 'ܚܙܝ ܣܓܠ̈ܐ ܦܪ̈ܨܘܦܝܐ',
'right-block'          => 'ܚܪܘܡ ܡܦܠܚܢ̈ܐ ܐܚܪ̈ܢܐ ܡܢ ܫܚܠܦܬܐ',
'right-mergehistory'   => 'ܚܒܘܛ ܬܫܥܝܬܐ ܕܦܐܬܬ̈ܐ',
'right-userrights'     => 'ܫܚܠܦ ܟܠ ܙܕ̈ܩܐ ܕܡܦܠܚܢܐ',

# User rights log
'rightslog'  => 'ܣܓܠܐ ܕܙܕ̈ܩܐ ܕܡܦܠܚܢܐ',
'rightsnone' => '(ܠܐ ܡܕܡ)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'           => 'ܩܪܝ ܦܐܬܐ ܗܕܐ',
'action-edit'           => 'ܫܚܠܦ ܦܐܬܐ ܗܕܐ',
'action-createpage'     => 'ܒܪܝ ܦܐܬܬ̈ܐ',
'action-createtalk'     => 'ܒܪܝ ܦܐܬܐ ܕܕܘܪܫܐ',
'action-createaccount'  => 'ܒܪܝ ܚܘܫܒܢܐ ܕܗܢܐ ܡܦܠܚܢܐ',
'action-minoredit'      => 'ܫܘܕܥ ܥܠ ܫܘܚܠܦܐ ܗܢܐ ܐܝܟ ܙܥܘܪܐ',
'action-move'           => 'ܫܢܝ ܦܐܬܐ ܗܕܐ',
'action-movefile'       => 'ܫܢܝ ܗܢܐ ܠܦܦܐ',
'action-upload'         => 'ܐܣܩ ܗܢܐ ܠܦܦܐ',
'action-delete'         => 'ܫܘܦ ܦܐܬܐ ܗܕܐ',
'action-deleterevision' => 'ܫܘܦ ܬܢܝܬܐ ܗܕܐ',
'action-deletedhistory' => 'ܚܙܝ ܬܫܥܝܬܐ ܫܝܦܬܐ ܕܦܐܬܐ ܗܕܐ',
'action-browsearchive'  => 'ܒܨܝ ܦܐܬܬ̈ܐ ܫܝܦܬ̈ܐ',
'action-undelete'       => 'ܠܐ ܫܘܦ ܦܐܬܐ ܗܕܐ',
'action-suppressionlog' => 'ܚܙܝ ܗܢܐ ܣܓܠܐ ܦܪܨܘܦܝܐ',
'action-block'          => 'ܚܪܘܡ ܡܦܠܚܢܐ ܗܢܐ ܡܢ ܫܚܠܦܬܐ',
'action-mergehistory'   => 'ܚܒܘܛ ܬܫܥܝܬܐ ܕܦܐܬܐ ܗܕܐ',
'action-userrights'     => 'ܫܚܠܦ ܟܠ ܙܕ̈ܩܐ ܕܡܦܠܚܢܐ',

# Recent changes
'nchanges'                     => '$1 {{PLURAL:$1|ܫܘܚܠܦܐ|ܫܘܚܠܦ̈ܐ}}',
'recentchanges'                => 'ܫܘܚܠܦ̈ܐ ܚܕ̈ܬܐ',
'recentchanges-legend'         => 'ܓܒܝܬ̈ܐ ܕܫܘܚܠܦ̈ܐ ܚܕ̈ܬܐ',
'recentchangestext'            => 'ܥܩܒ ܫܘܚܠܦ̈ܐ ܚܕܬ ܡܢ ܟܠ ܕܘܝܩܝ ܒܦܐܬܐ ܗܕܐ.',
'recentchanges-label-legend'   => 'ܩܠܝܕܐ: $1.',
'recentchanges-legend-newpage' => '$1 - ܦܐܬܐ ܚܕܬܐ',
'recentchanges-label-newpage'  => 'ܫܘܚܠܦܐ ܗܢܐ ܒܪܐ ܦܐܬܐ ܚܕܬܐ',
'recentchanges-legend-minor'   => '$1 - ܫܘܚܠܦܐ ܙܥܘܪܐ',
'recentchanges-label-minor'    => 'ܗܢܐ ܗܘ ܫܘܚܠܦܐ ܙܥܘܪܐ',
'recentchanges-legend-bot'     => '$1 - ܫܘܚܠܦܐ ܕܒܘܬ (bot)',
'recentchanges-label-bot'      => 'ܒܘܬ (bot) ܥܒܕ ܗܢܐ ܫܘܚܠܦܐ',
'rclistfrom'                   => 'ܚܘܝ ܫܘܚܠܦ̈ܐ ܚܕ̈ܬܐ ܡܢ $1',
'rcshowhideminor'              => '$1 ܫܘܚܠܦ̈ܐ ܙܥܘܪ̈ܐ',
'rcshowhidebots'               => '$1 ܒܘܬ̈ܐ (Bots)',
'rcshowhideliu'                => '$1 ܡܦܠܚܢ̈ܐ ܥܠܝܠ̈ܐ',
'rcshowhideanons'              => '$1 ܡܦܠܚܢܐ ܠܐ ܝܕܝܥܐ',
'rcshowhidemine'               => '$1 ܫܘܚܠܦ̈ܝ',
'rclinks'                      => 'ܚܘܝ $1 ܫܘܚܠܦ̈ܐ ܐܚܪ̈ܝܐ ܒ $2 ܝܘܡܬ̈ܐ ܐܚܪ̈ܝܬܐ<br />$3',
'diff'                         => 'ܦܪܝܫܘܬܐ',
'hist'                         => 'ܬܫܥܝܬܐ',
'hide'                         => 'ܛܫܝ',
'show'                         => 'ܚܘܝ',
'minoreditletter'              => 'ܙ',
'newpageletter'                => 'ܚ',
'boteditletter'                => 'ܒ',
'newsectionsummary'            => '/* $1 */ ܡܢܬܐ ܚܕܬܐ',
'rc-enhanced-hide'             => 'ܛܫܝ ܐܪ̈ܝܟܬܐ',

# Recent changes linked
'recentchangeslinked'         => 'ܫܘܚܠܦ̈ܐ ܕ̈ܡܝܐ',
'recentchangeslinked-feed'    => 'ܫܘܚܠܦ̈ܐ ܕ̈ܡܝܐ',
'recentchangeslinked-toolbox' => 'ܫܘܚܠܦ̈ܐ ܕ̈ܡܝܐ',
'recentchangeslinked-page'    => 'ܫܡܐ ܕܦܐܬܐ:',

# Upload
'upload'            => 'ܐܣܩ ܠܦܦܐ',
'uploadbtn'         => 'ܐܣܩ ܠܦܦܐ',
'uploadnologin'     => 'ܠܐ ܥܠܝܠܐ',
'uploaderror'       => 'ܦܘܕܐ ܒܡܣܩܬܐ',
'uploadlog'         => 'ܣܓܠܐ ܕܣܠܩܐ',
'uploadlogpage'     => 'ܣܓܠܐ ܕܣܠܩܐ',
'filename'          => 'ܫܡܐ ܕܠܦܦܐ',
'filedesc'          => 'ܦܣܝܩܬ̈ܐ',
'fileuploadsummary' => 'ܦܣܝܩܬ̈ܐ:',
'filesource'        => 'ܥܩܪܐ:',
'uploadwarning'     => 'ܐܣܩ ܙܘܗܪܐ',
'savefile'          => 'ܠܒܘܟ ܠܦܦܐ',
'watchthisupload'   => 'ܪܗܝ ܗܢܐ ܠܦܦܐ',

'upload-file-error' => 'ܦܘܕܐ ܓܘܝܐ',

'license-nopreview'  => '(ܠܝܬ ܚܝܪܐ ܩܕܡܝܐ)',
'upload_source_file' => ' (ܠܦܦܐ ܥܠ ܚܫܘܒܬܐ ܕܝܠܟ)',

# Special:ListFiles
'imgfile'        => 'ܠܦܦܐ',
'listfiles_date' => 'ܣܝܩܘܡܐ',
'listfiles_name' => 'ܫܡܐ',
'listfiles_user' => 'ܡܦܠܚܢܐ',

# File description page
'file-anchor-link'          => 'ܠܦܦܐ',
'filehist'                  => 'ܬܫܥܝܬܐ ܕܠܦܦܐ',
'filehist-deleteall'        => 'ܫܘܦ ܟܠ',
'filehist-deleteone'        => 'ܫܘܦ',
'filehist-current'          => 'ܗܫܝܐ',
'filehist-datetime'         => 'ܣܝܩܘܡܐ/ܙܒܢܐ',
'filehist-thumb'            => 'ܨܘܪܬܐ ܙܥܘܪܬܐ',
'filehist-nothumb'          => 'ܠܐ ܙܥܘܪܬܐ',
'filehist-user'             => 'ܡܦܠܚܢܐ',
'imagelinks'                => 'ܐܣܘܪ̈ܐ ܕܠܦܦܐ',
'linkstoimage'              => '{{PLURAL:$1|ܦܐܬܐ ܗܕܐ ܐܣܪ|$1 ܦܐܬܬ̈ܐ ܗܠܝܢ ܐܣܪܝܢ}} ܥܡ ܗܢܐ ܠܦܦܐ:',
'nolinkstoimage'            => 'ܠܝܬ ܦܐܬܐ ܕܐܣܪ ܠܗܢܐ ܠܦܦܐ.',
'redirectstofile'           => '{{PLURAL:$1|ܠܦܦܐ|$1 ܠܦܦ̈ܐ}} ܕܐܬܐ ܐܣܪ ܠܗܢܐ ܠܦܦܐ:',
'uploadnewversion-linktext' => 'ܐܣܩ ܨܚܚܐ ܚܕܬܐ ܡܢ ܗܢܐ ܠܦܦܐ',
'shared-repo-from'          => 'ܡܢ $1',

# File deletion
'filedelete'                  => 'ܫܘܦ $1',
'filedelete-legend'           => 'ܫܘܦ ܠܦܦܐ',
'filedelete-comment'          => 'ܥܠܬܐ ܕܫܝܦܐ:',
'filedelete-submit'           => 'ܫܘܦ',
'filedelete-nofile'           => "'''$1''' ܠܝܬ.",
'filedelete-otherreason'      => 'ܥܠܬܐ ܐܚܪܬܐ:',
'filedelete-reason-otherlist' => 'ܥܠܬܐ ܐܚܪܬܐ',
'filedelete-edit-reasonlist'  => 'ܫܚܠܦ ܥܠܠܬ̈ܐ ܕܫܝܦܐ',

# MIME search
'download' => 'ܢܚܬ',

# Unwatched pages
'unwatchedpages' => 'ܦܐܬܬ̈ܐ ܠܐ ܪ̈ܗܝܬܐ',

# List redirects
'listredirects' => 'ܒܪܒܝܢ ܕܨܘܝܒ̈ܐ',

# Unused templates
'unusedtemplateswlh' => 'ܐܣܘܪ̈ܐ ܐܚܪ̈ܢܐ',

# Random page
'randompage'         => 'ܡܓܠ̈ܐ ܚܘܝܚ̈ܐ',
'randompage-nopages' => 'ܠܝܬ ܦܐܬܬ̈ܐ ܒ{{PLURAL:$2|ܚܩܠܐ ܕ|ܚܩܠܬ̈ܐ ܕ}}: $1.',

# Random redirect
'randomredirect'         => 'ܨܘܝܒ̈ܐ ܚܘܝܚ̈ܐ',
'randomredirect-nopages' => 'ܠܝܬ ܨܘܝܒ̈ܐ ܒܚܩܠܐ ܕ "$1".',

# Statistics
'statistics-pages' => 'ܦܐܬܬ̈ܐ',

'disambiguations'     => 'ܦܐܬܬ̈ܐ ܕܠܐ ܕܠܘܚܝܐ',
'disambiguationspage' => 'Template:ܠܐ ܕܠܘܚܝܐ',

'doubleredirects'            => 'ܨܘܝܒ̈ܐ ܥܦܝܦ̈ܐ',
'double-redirect-fixed-move' => '[[$1]] ܐܫܬܢܝܬ.
ܗܫܐ ܐܝܬܝܗܝ  ܨܘܝܒܐ ܠ [[$2]].',

'brokenredirects'        => 'ܨܘܝܒ̈ܐ ܬܒܝܪ̈ܐ',
'brokenredirects-edit'   => 'ܫܚܠܦ',
'brokenredirects-delete' => 'ܫܘܦ',

'withoutinterwiki'        => 'ܦܐܬܬ̈ܐ ܕܠܐ ܐܣܘܪ̈ܐ ܕܠܫܢ̈ܐ ܐܚܪ̈ܢܐ',
'withoutinterwiki-submit' => 'ܚܘܝ',

'fewestrevisions' => 'ܦܐܬܬ̈ܐ ܥܡ ܬܢܝܬ̈ܐ ܒܨܝܪ ܡܢ ܟܠ',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|ܒܐܝܛ|ܒܐܝܛ̈ܐ}}',
'ncategories'             => '$1 {{PLURAL:$1|ܣܕܪܐ|ܣܕܪ̈ܐ}}',
'nlinks'                  => '$1 {{PLURAL:$1|ܐܣܘܪܐ|ܐܣܘܪ̈ܐ}}',
'nmembers'                => '$1 {{PLURAL:$1|ܗܕܡܐ|ܗܕ̈ܡܐ}}',
'nrevisions'              => '$1 {{PLURAL:$1|ܬܢܝܬܐ|ܬܢܝܬ̈ܐ }}',
'lonelypages'             => 'ܦܐܬܬ̈ܐ ܝܬܡܬ̈ܐ',
'uncategorizedpages'      => 'ܦܐܬܬ̈ܐ ܠܐ ܣܕܝܪ̈ܬܐ',
'uncategorizedcategories' => 'ܣܕܪ̈ܐ ܠܐ ܣܕܝܪ̈ܐ',
'uncategorizedimages'     => 'ܠܦܦ̈ܐ ܠܐ ܣܕܝܪ̈ܐ',
'uncategorizedtemplates'  => 'ܩܠܒ̈ܐ ܠܐ ܣܕܝܪ̈ܐ',
'wantedcategories'        => 'ܣܕܪ̈ܐ ܒܥܝ̈ܐ',
'wantedpages'             => 'ܦܐܬܬ̈ܐ ܒܥܝܬ̈ܐ',
'wantedfiles'             => 'ܠܦܦ̈ܐ ܒܥܝ̈ܐ',
'wantedtemplates'         => 'ܩܠܒ̈ܐ ܒܥܝ̈ܐ',
'shortpages'              => 'ܦܐܬܬ̈ܐ ܟܪ̈ܝܬܐ',
'longpages'               => 'ܦܐܬܬ̈ܐ ܐܪ̈ܝܟܬܐ',
'deadendpages'            => 'ܦܐܬܬ̈ܐ ܥܡ ܚܪܬܐ ܡܝܬܬܐ',
'protectedpages'          => 'ܦܐܬܬ̈ܐ ܢܛܝܪ̈ܬܐ',
'listusers'               => 'ܒܪܒܝܢ ܕܗܕ̈ܡܐ',
'listusers-editsonly'     => 'ܚܘܝ ܡܦܠܚܢ̈ܐ ܥܡ ܫܘܚܠܦ̈ܐ ܒܠܚܘܕ',
'listusers-creationsort'  => 'ܛܟܣ ܒܣܝܩܘܡܐ ܕܒܪܝܐ',
'usereditcount'           => '$1 {{PLURAL:$1|ܫܘܚܠܦܐ|ܫܘܚܠܦ̈ܐ}}',
'usercreated'             => 'ܒܪܐ ܒܣܝܩܘܡ $1 ܫܥܬܐ $2',
'newpages'                => 'ܦܐܬܬ̈ܐ ܚܕ̈ܬܬܐ',
'newpages-username'       => 'ܫܡܐ ܕܡܦܠܚܢܐ:',
'ancientpages'            => 'ܦܐܬܬ̈ܐ ܥܬܝܩ ܡܢ ܟܠ',
'move'                    => 'ܫܢܝ',
'movethispage'            => 'ܫܢܝ ܦܐܬܐ ܗܕܐ',
'notargettitle'           => 'ܠܐ ܢܘܦܐ',
'pager-newer-n'           => '{{PLURAL:$1|1 1 ܚܕܬܐ|$1 ܚܕ̈ܬܐ}}',
'pager-older-n'           => '{{PLURAL:$1|1 ܥܬܝܩ̈ܐ 1|$1 ܥܬܝܩ̈ܐ}}',
'suppress'                => 'ܚܝܘܪܐ',

# Book sources
'booksources'               => 'ܙܠ',
'booksources-search-legend' => 'ܒܨܝ ܥܠ ܡܒܘܥ̈ܐ ܕܟܬܒ̈ܐ',
'booksources-go'            => 'ܙܠ',

# Special:Log
'specialloguserlabel' => 'ܡܦܠܚܢܐ:',
'log'                 => 'ܣܓܠ̈ܐ',
'all-logs-page'       => 'ܟܠ ܣܓܠ̈ܐ ܓܘܢܝ̈ܐ',

# Special:AllPages
'allpages'          => 'ܟܠ ܦܐܬܬ̈ܐ',
'alphaindexline'    => '$1 ܠ $2',
'prevpage'          => 'ܦܐܬܐ ܩܕܝܡܬܐ ($1)',
'allpagesfrom'      => 'ܚܘܝ ܦܐܬܬ̈ܐ ܕܫܪܐ ܥܡ:',
'allpagesto'        => 'ܚܘܝ ܦܐܬܬ̈ܐ ܕܫܠܡ ܥܡ:',
'allarticles'       => 'ܟܠ ܡܓܠ̈ܐ',
'allinnamespace'    => 'ܟܠ ܦܐܬܬ̈ܐ (ܚܩܠܐ ܕ $1)',
'allnotinnamespace' => 'ܟܠ ܦܐܬܬ̈ܐ (ܠܐ ܒܚܩܠܐ ܕ $1)',
'allpagesprev'      => 'ܩܕܡ',
'allpagesnext'      => 'ܒܬܪ',
'allpagessubmit'    => 'ܙܠ',
'allpages-bad-ns'   => '{{SITENAME}} ܠܝܬ ܠܗ ܚܩܠܐ "$1".',

# Special:Categories
'categories'                    => 'ܣܕܪ̈ܐ',
'special-categories-sort-count' => 'ܛܟܣ ܒܡܢܝܢܐ',
'special-categories-sort-abc'   => 'ܛܟܣ ܗܓܝܢܐܝܬ',

# Special:DeletedContributions
'deletedcontributions'             => 'ܫܘܬܦܘܝܬ̈ܐ ܕܡܦܠܚܢܐ ܫܝܦܬ̈ܐ',
'deletedcontributions-title'       => 'ܫܘܬܦܘܝܬ̈ܐ ܕܡܦܠܚܢܐ ܫܝܦܬ̈ܐ',
'sp-deletedcontributions-contribs' => 'ܫܘܬܦܘܝܬ̈ܐ',

# Special:LinkSearch
'linksearch'    => 'ܐܣܘܪܐ ܒܪܝܐ',
'linksearch-ns' => 'ܚܩܠܐ:',
'linksearch-ok' => 'ܒܨܝ',

# Special:ListUsers
'listusers-submit' => 'ܚܘܝ',

# Special:ActiveUsers
'activeusers-count' => '$1 {{PLURAL:$1|ܫܘܚܠܦܐ ܚܕܬܐ|ܫܘܚܠܦ̈ܐ ܚܕ̈ܬܐ}} ܒ {{PLURAL:$3|ܝܘܡܐ ܐܚܪܝܐ|$3 ܝܘܡܬ̈ܐ ܐܚܪ̈ܝܬܐ}}',
'activeusers-from'  => 'ܚܘܝ ܡܦܠܚܢ̈ܐ ܕܫܪܐ ܥܡ:',

# Special:Log/newusers
'newuserlogpage'           => 'ܣܓܠܐ ܕܒܪܝܬܐ ܕܡܦܠܚܢܐ',
'newuserlog-create-entry'  => 'ܚܘܫܒܢܐ ܕܡܦܠܚܢܐ ܚܕܬܐ',
'newuserlog-create2-entry' => 'ܒܪܐ ܚܘܫܒܢܐ ܚܕܬܐ $1',

# Special:ListGroupRights
'listgrouprights'                      => 'ܙܕ̈ܩܐ ܕܟܢܘܫܬܐ ܕܡܦܠܚܢ̈ܐ',
'listgrouprights-group'                => 'ܟܢܘܫܬܐ',
'listgrouprights-rights'               => 'ܙܕ̈ܩܐ',
'listgrouprights-helppage'             => 'Help:ܙܕܩ̈ܐ ܕܟܢܘܫܬܐ',
'listgrouprights-members'              => '(ܒܪܒܝܢ ܕܗܕ̈ܡܐ)',
'listgrouprights-addgroup'             => 'ܐܘܣܦ {{PLURAL:$2|ܟܢܘܫܬܐ|ܟܢܘܫܬ̈ܐ}}: $1',
'listgrouprights-removegroup'          => 'ܠܚܝ {{PLURAL:$2|ܟܢܘܫܬܐ|ܟܢܘܫܬ̈ܐ}}: $1',
'listgrouprights-addgroup-all'         => 'ܐܘܣܦ ܟܠ ܟܢܘܫܬ̈ܐ',
'listgrouprights-removegroup-all'      => 'ܠܚܝ ܟܠ ܟܢܘܫܬ̈ܐ',
'listgrouprights-removegroup-self'     => 'ܠܚܝ {{PLURAL:$2|ܟܢܘܫܬܐ|ܟܢܘܫܬ̈ܐ}} ܡܢ ܚܘܫܒܢܗ ܕܝܠܢܝܐ: $1',
'listgrouprights-removegroup-self-all' => 'ܠܚܝ ܟܠ ܟܢܘܫܬ̈ܐ ܡܢ ܚܘܫܒܢܗ ܕܝܠܢܝܐ',

# E-mail user
'emailuser'       => 'ܫܕܪ ܐܓܪܬܐ ܠܗܢܐ ܡܦܠܚܢܐ',
'emailpage'       => 'ܫܕܪ ܐܓܪܬܐ ܒܒܝܠܕܪܐ ܐܠܩܛܪܘܢܝܐ ܠܡܦܠܚܢܐ',
'defemailsubject' => 'ܒܝܠܕܪܐ ܐܠܩܛܪܘܢܝܐ  ܡܢ {{SITENAME}}',
'noemailtitle'    => 'ܠܝܬ ܒܝܠܕܪܐ ܐܠܩܛܪܘܢܝܐ',
'email-legend'    => 'ܫܕܪ ܐܓܪܬܐ ܠܡܦܠܚܢܐ ܕ {{SITENAME}} ܐܚܪܢܐ',
'emailfrom'       => 'ܡܢ:',
'emailto'         => 'ܠ:',
'emailsubject'    => 'ܡܠܘܐܐ:',
'emailmessage'    => 'ܐܓܪܬܐ:',
'emailsend'       => 'ܫܕܪ',
'emailccme'       => 'ܫܕܪ ܠܝ ܨܚܚܐ ܡܢ ܐܓܪ̈ܬܐ ܕܝܠܝ.',
'emailccsubject'  => 'ܨܚܚܐ ܕܐܓܪܬܟ ܠ $1: $2',

# Watchlist
'watchlist'         => 'ܪ̈ܗܝܬܝ',
'mywatchlist'       => 'ܪ̈ܗܝܬܝ',
'watchlistfor'      => "(ܠ'''$1''')",
'nowatchlist'       => 'ܠܝܬ ܠܟ ܡܕܡ ܒܪ̈ܗܝܬܐ ܕܝܠܟ',
'watchnologin'      => 'ܠܐ ܥܠܝܠܐ',
'addedwatch'        => 'ܐܘܣܦ ܠܪ̈ܗܝܬܐ',
'removedwatch'      => 'ܠܚܝܬ ܡܢ ܪ̈ܗܝܬܟ',
'removedwatchtext'  => 'ܦܐܬܐ "[[:$1]]" ܐܫܬܩܠܬ ܡܢ [[Special:Watchlist|ܪ̈ܗܝܬܟ]].',
'watch'             => 'ܪܗܝ',
'watchthispage'     => 'ܪܗܝ ܗܕܐ ܦܐܬܐ',
'unwatch'           => 'ܠܐ ܪܗܝ',
'unwatchthispage'   => 'ܟܠܝ ܪܗܝܐ',
'wlshowlast'        => 'ܚܘܝ $1 ܫܥܬ̈ܐ $2 ܝܘܡܬ̈ܐ ܐܚܪ̈ܝܬܐ $3',
'watchlist-options' => 'ܓܒܝܬ̈ܐ ܕܪ̈ܗܝܬܐ',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'ܪܗܝܐ...',
'unwatching' => 'ܠܐ ܪܗܝܐ...',

'enotif_newpagetext'           => 'ܗܕܐ ܗܝ ܦܐܬܐ ܚܕܬܐ',
'enotif_impersonal_salutation' => 'ܡܦܠܚܢܐ {{SITENAME}}',
'created'                      => 'ܒܪܐ',
'enotif_lastdiff'              => 'ܚܙܝ $1 ܠܚܙܝܐ ܕܫܘܚܠܦܐ ܗܢܐ.',
'enotif_anon_editor'           => 'ܡܦܠܚܢܐ ܠܐ ܝܕܝܥܐ $1',

# Delete
'deletepage'             => 'ܫܘܦ ܦܐܬܐ',
'confirm'                => 'ܚܬܬ',
'excontent'              => "ܚܒܝܫܬ̈ܐ ܗܘܬ: '$1'",
'excontentauthor'        => "ܚܒܝܫܬ̈ܐ ܗܘܬ: '$1' (ܘܫܘܬܦܢܐ ܝܚܝܕܝܐ ܗܘܐ '[[Special:Contributions/$2|$2]]')",
'exblank'                => 'ܦܐܬܐ ܣܦܝܩܬܐ ܗܘܐ',
'delete-confirm'         => 'ܫܘܦ "$1"',
'delete-legend'          => 'ܫܘܦ',
'actioncomplete'         => 'ܥܡܠܝܬܐ ܓܡܪܬ',
'deletedarticle'         => 'ܫܦ "[[$1]]"',
'dellogpage'             => 'ܣܓܠܐ ܕܫܝܦܐ',
'deletionlog'            => 'ܣܓܠܐ ܕܫܝܦܐ',
'deletecomment'          => 'ܥܠܬܐ ܕܫܝܦܐ:',
'deleteotherreason'      => 'ܥܠܬܐ ܐܚܪܬܐ/ܝܬܝܪܬܐ:',
'deletereasonotherlist'  => 'ܥܠܬܐ ܐܚܪܬܐ',
'delete-edit-reasonlist' => 'ܫܚܠܦ ܥܠܠܬ̈ܐ ܕܫܝܦܐ',

# Rollback
'editcomment' => "ܦܣܝܩܬ̈ܐ ܕܫܘܚܠܦܐ ܗܘܐ: \"''\$1''\".",

# Protect
'protectlogpage'              => 'ܣܓܠܐ ܕܢܛܪܐ',
'protectedarticle'            => 'ܢܛܝܪܐ "[[$1]]"',
'unprotectedarticle'          => 'ܠܐ ܢܛܝܪܐ "[[$1]]"',
'protect-legend'              => 'ܚܬܬ ܢܘܛܪܐ',
'protectcomment'              => 'ܥܠܬܐ:',
'protect-fallback'            => 'ܒܥܝ "$1" ܦܣܣܐ',
'protect-level-autoconfirmed' => 'ܚܪܘܡ ܡܦܠܚܢ̈ܐ ܚܕ̈ܬܐ ܘܠܐ ܥܠܝܠ̈ܐ',
'protect-level-sysop'         => 'ܡܕܒܪ̈ܢܐ ܒܠܚܘܕ',
'protect-othertime'           => 'ܥܕܢܐ ܐܚܪܬܐ:',
'protect-othertime-op'        => 'ܥܕܢܐ ܐܚܪܬܐ',
'protect-otherreason'         => 'ܥܠܬܐ ܐܚܪܬܐ/ܢܩܝܦܬܐ:',
'protect-otherreason-op'      => 'ܥܠܬܐ ܐܚܪܬܐ/ܢܩܝܦܬܐ',
'protect-edit-reasonlist'     => 'ܫܚܠܦ ܥܠܬܐ ܕܢܛܪܐ',
'protect-expiry-options'      => '1 ܫܥܬܐ:1 hour,1 ܝܘܡܐ:1 day,1 ܫܒܘܥܐ:1 week,2 ܫܒܘܥ̈ܐ:2 weeks,1 ܝܪܚܐ:1 month,3 ܝܪ̈ܚܐ:3 months,6 ܝܪ̈ܚܐ:6 months,1 ܫܢܬܐ:1 year,ܕܠܐ ܣܟ:infinite',
'restriction-type'            => 'ܦܣܣܐ:',

# Restrictions (nouns)
'restriction-edit'   => 'ܫܚܠܦ',
'restriction-move'   => 'ܫܢܝ',
'restriction-create' => 'ܒܪܝ',
'restriction-upload' => 'ܐܣܩ',

# Undelete
'undelete'                  => 'ܚܙܝ ܦܐܬܬ̈ܐ ܫܝܦܬ̈ܐ',
'viewdeletedpage'           => 'ܚܙܝ ܦܐܬܬ̈ܐ ܫܝܦܬ̈ܐ',
'undelete-revision'         => 'ܫܦ ܬܢܝܬܐ ܕ $1 (ܒܣܝܩܘܡ $4, ܒ $5) ܒܝܕ $3:',
'undelete-nodiff'           => 'ܠܝܬ ܬܢܝܬܐ ܥܬܝܩܬܐ.',
'undeleteviewlink'          => 'ܚܙܝ',
'undeleteinvert'            => 'ܐܗܦܟ ܠܓܘܒܝܐ',
'undelete-header'           => 'ܚܙܝ [[Special:Log/delete|ܣܓܠܐ ܕܫܝܦܐ]] ܠܚܙܝܐ ܕܦܐܬܬ̈ܐ ܫܝܦܬ̈ܐ ܚܕ̈ܬܬܐ.',
'undelete-search-box'       => 'ܒܨܝ ܦܐܬܬ̈ܐ ܫܝܦܬ̈ܐ',
'undelete-search-prefix'    => 'ܚܘܝ ܦܐܬܬ̈ܐ ܫܪܝܢ ܒ:',
'undelete-search-submit'    => 'ܒܨܝ',
'undelete-show-file-submit' => 'ܐܝܢ',

# Namespace form on various pages
'namespace'      => 'ܚܩܠܐ:',
'invert'         => 'ܐܗܦܟ ܠܓܘܒܝܐ',
'blanknamespace' => '(ܪܫܝܬܐ)',

# Contributions
'contributions'       => 'ܫܘܬܦܘܝܬ̈ܐ ܕܡܦܠܚܢܐ',
'contributions-title' => 'ܫܘܬܦܘܝܬ̈ܐ ܕܡܦܠܚܢܐ ܠ$1',
'mycontris'           => 'ܫܘܬܦܘܝܬ̈ܝ',
'contribsub2'         => 'ܕ $1 ($2)',
'uctop'               => '(ܥܠܝܐ)',
'month'               => 'ܡܢ ܝܪܚܐ ܕ (ܘܡܢ ܩܕܡ ܗܝܕܝܢ):',
'year'                => 'ܡܢ ܫܢܬ (ܘܡܢ ܩܕܡ ܗܝܕܝܢ):',

'sp-contributions-newbies'       => 'ܚܘܝ ܫܘܬܦܘܝܬ̈ܐ ܕ ܚܘܫܒܢ̈ܐ ܚܕ̈ܬܐ ܒܠܚܘܕ',
'sp-contributions-newbies-sub'   => 'ܠܚܘܫܒܢ̈ܐ ܚܕ̈ܬܐ',
'sp-contributions-newbies-title' => 'ܫܘܬܦܘܝܬ̈ܐ ܕܡܦܠܚܢܐ ܠܚܘܫܒܢ̈ܐ ܚܕ̈ܬܐ',
'sp-contributions-blocklog'      => 'ܣܓܠܐ ܕܚܪܡܐ',
'sp-contributions-deleted'       => 'ܫܘܬܦܘܝܬ̈ܐ ܕܡܦܠܚܢܐ ܫܝܦܬ̈ܐ',
'sp-contributions-logs'          => 'ܣܓܠ̈ܐ',
'sp-contributions-talk'          => 'ܡܡܠܠܐ',
'sp-contributions-search'        => 'ܒܨܝ ܫܘܬܦܘܝܬ̈ܐ',
'sp-contributions-username'      => 'ܐܝ ܦܝ (IP) ܐܘ ܫܡܐ ܕܡܦܠܚܢܐ:',
'sp-contributions-submit'        => 'ܒܨܝ',

# What links here
'whatlinkshere'            => 'ܡܐ ܐܣܪ ܠܟܐ',
'whatlinkshere-title'      => 'ܦܐܬܬ̈ܐ ܕܐܣܝܪܝܢ ܥܡ "$1"',
'whatlinkshere-page'       => 'ܦܐܬܐ:',
'linkshere'                => "ܦܐܬܬ̈ܐ ܗܠܝܢ ܐܣܝܪܝܢ ܥܡ '''[[:$1]]''':",
'nolinkshere'              => "ܠܝܬ ܦܐܬܬ̈ܐ ܐܣܪܝܢ ܥܡ '''[[:$1]]'''.",
'nolinkshere-ns'           => "ܠܝܬ ܦܐܬܬ̈ܐ ܐܣܪܝܢ ܥܡ '''[[:$1]]''' ܒܚܩܠܐ ܓܒܝܐ.",
'isredirect'               => 'ܦܐܬܐ ܕܨܘܝܒܐ',
'isimage'                  => 'ܐܣܘܪܐ ܕܨܘܪܬܐ',
'whatlinkshere-prev'       => '{{PLURAL:$1|ܩܕܝܡܐ|$1 ܩܕ̈ܝܡܐ}}',
'whatlinkshere-next'       => '{{PLURAL:$1|ܚܕܬܐ|$1 ܚܕ̈ܬܐ}}',
'whatlinkshere-links'      => '← ܐܣܘܪ̈ܐ',
'whatlinkshere-hideredirs' => '$1 ܨܘܝܒ̈ܐ',
'whatlinkshere-hidelinks'  => '$1 ܐܣܘܪ̈ܐ',
'whatlinkshere-hideimages' => '$1 ܐܣܘܪܐ ܕܨܘܪܬܐ',

# Block/unblock
'blockip'                    => 'ܚܪܘܡ ܡܦܠܚܢܐ',
'blockip-legend'             => 'ܚܪܘܡ ܡܦܠܚܢܐ',
'ipaddress'                  => 'ܐܝ ܦܝ (IP):',
'ipadressorusername'         => 'ܐܝ ܦܝ (IP) ܐܘ ܫܡܐ ܕܡܦܠܚܢܐ:',
'ipbreason'                  => 'ܥܠܬܐ:',
'ipbreasonotherlist'         => 'ܥܠܬܐ ܐܚܪܬܐ',
'ipbanononly'                => 'ܚܪܘܡ ܡܦܠܚܢ̈ܐ ܠܐ ܝܕ̈ܝܥܐ ܒܠܚܘܕ',
'ipbsubmit'                  => 'ܚܪܘܡ ܡܦܠܚܢܐ ܗܢܐ',
'ipbother'                   => 'ܥܕܢܐ ܐܚܪܬܐ',
'ipboptions'                 => '2 ܫܥܬ̈ܐ:2 hours,1 ܝܘܡܐ:1 day,3 ܝܘܡܬ̈ܐ:3 days,1 ܫܒܘܥܐ:1 week,2 ܫܒܘܥ̈ܐ:2 weeks,1 ܝܪܚܐ:1 month,3 ܝܪ̈ܚܐ:3 months,6 ܝܪ̈ܚܐ:6 months,1 ܫܢܬܐ:1 year,ܕܠܐ ܣܟ:infinite',
'ipbotheroption'             => 'ܐܚܪܢܐ',
'ipbotherreason'             => 'ܥܠܬܐ ܐܚܪܬܐ/ܢܩܝܦܬܐ:',
'ipbhidename'                => 'ܛܫܝ ܫܡܐ ܕܡܦܠܚܢܐ ܡܢ ܫܘܚܠܦ̈ܐ ܘܒܪ̈ܒܝܢ',
'badipaddress'               => 'ܐܝ ܦܝ (IP) ܠܐ ܬܪܝܨܐ:',
'blockipsuccesssub'          => 'ܚܪܡܐ ܓܡܪ',
'ipb-edit-dropdown'          => 'ܫܚܠܦ ܥܠܠܬ̈ܐ ܕܚܪܡܐ',
'ipb-unblock-addr'           => 'ܫܩܘܠ ܚܪܡܐ ܡܢ $1',
'ipb-unblock'                => 'ܫܩܘܠ ܚܪܡܐ ܡܢ ܐܝ ܦܝ (IP) ܐܘ ܫܡܐ ܕܡܦܠܚܢܐ',
'ipb-blocklist-contribs'     => 'ܫܘܬܦܘܝܬ̈ܐ ܕ $1',
'unblockip'                  => 'ܫܩܘܠ ܚܪܡܐ ܡܢ ܡܦܠܚܢܐ',
'ipusubmit'                  => 'ܫܩܘܠ ܚܪܡܐ ܗܢܐ',
'unblocked'                  => 'ܐܫܬܩܠ ܚܪܡܐ ܡܢ [[User:$1|$1]]',
'ipblocklist'                => 'ܐܝ ܦܝ (IP) ܘܫܡܗ̈ܐ ܕܡܦܠܚܢ̈ܐ ܚܪ̈ܝܡܐ',
'ipblocklist-legend'         => 'ܐܫܟܚ ܡܦܠܚܢܐ ܡܚܪܡܐ',
'ipblocklist-username'       => 'ܐܝ ܦܝ (IP) ܐܘ ܫܡܐ ܕܡܦܠܚܢܐ:',
'ipblocklist-sh-userblocks'  => '$1 ܚܪ̈ܡܐ ܕܚܘܫܒܢܐ',
'ipblocklist-sh-tempblocks'  => '$1 ܚܪ̈ܡܐ ܙܒܢܢܝ̈ܐ',
'ipblocklist-submit'         => 'ܒܨܝ',
'blocklistline'              => '$1, $2 ܚܪܡ $3 ($4)',
'infiniteblock'              => 'ܕܠܐ ܣܟ',
'anononlyblock'              => 'ܠܐ ܝܕ̈ܝܥܐ ܒܠܚܘܕ',
'ipblocklist-empty'          => 'ܣܓܠܐ ܕܚܪܡܐ ܣܦܝܩܐ.',
'blocklink'                  => 'ܚܪܘܡ',
'unblocklink'                => 'ܫܩܘܠ ܚܪܡܐ',
'change-blocklink'           => 'ܫܚܠܦ ܚܪܡܐ',
'contribslink'               => 'ܫܘܬܦܘܝܬ̈ܐ',
'blocklogpage'               => 'ܣܓܠܐ ܕܚܪܡܐ',
'blocklogentry'              => 'ܚܪܡ [[$1]] ܠܡܬܚܐ ܕ $2 $3',
'unblocklogentry'            => 'ܫܩܠ ܚܪܡܐ ܡܢ $1',
'block-log-flags-anononly'   => 'ܡܦܠܚܢ̈ܐ ܠܐ ܝܕ̈ܝܥܐ ܒܠܚܘܕ',
'block-log-flags-hiddenname' => 'ܫܡܐ ܕܡܦܠܚܢܐ ܛܘܫܝܐ',
'ipb_already_blocked'        => '"$1" ܡܚܪܡܐ ܗܘ ܡܢ ܟܕܘ',
'ipb-needreblock'            => '==ܡܚܪܡܐ ܡܢ ܟܕܘ==
"$1" ܡܚܪܡܐ ܗܘ ܡܢ ܟܕܘ
Do you want to change the settings?',

# Move page
'move-page'               => 'ܫܢܝ $1',
'move-page-legend'        => 'ܫܢܝ ܦܐܬܐ',
'movearticle'             => 'ܫܢܝ ܦܐܬܐ:',
'movenologin'             => 'ܠܐ ܥܠܝܠܐ',
'newtitle'                => 'ܠܫܡܐ ܚܕܬܐ:',
'move-watch'              => 'ܪܗܝ ܦܐܬܐ ܗܕܐ',
'movepagebtn'             => 'ܫܢܝ ܦܐܬܐ',
'pagemovedsub'            => 'ܫܘܢܝܐ ܓܡܪ',
'movepage-moved'          => '<big>\'\'\'"$1" ܐܫܬܢܝܬ ܠ "$2"\'\'\'</big>',
'movepage-moved-redirect' => 'ܨܘܝܒܐ ܐܬܒܪܝ',
'movedto'                 => 'ܐܬܫܢܝ ܠ',
'1movedto2'               => 'ܫܢܐ [[$1]] ܠ [[$2]]',
'1movedto2_redir'         => 'ܫܢܐ [[$1]] ܠ [[$2]] ܥܠ ܪܫ ܨܘܝܒܐ',
'movelogpage'             => 'ܣܓܠܐ ܕܫܘܢܝܐ',
'movereason'              => 'ܥܠܬܐ:',
'delete_and_move'         => 'ܫܘܦ ܘܫܢܝ',
'delete_and_move_confirm' => 'ܐܝܢ, ܫܘܦ ܦܐܬܐ',
'move-leave-redirect'     => 'ܫܒܘܩ ܨܘܝܒܐ ܒܬܪܟ',

# Export
'export'            => 'ܐܦܩ ܦܐܬܬ̈ܐ',
'export-submit'     => 'ܐܦܩ',
'export-addcattext' => 'ܐܘܣܦ ܦܐܬܬ̈ܐ ܡܢ ܣܕܪܐ:',
'export-addcat'     => 'ܐܘܣܦ',
'export-addnstext'  => 'ܐܘܣܦ ܦܐܬܬ̈ܐ ܡܢ ܚܩܠܐ:',
'export-addns'      => 'ܐܘܣܦ',
'export-download'   => 'ܠܒܘܟ ܐܝܟ ܠܦܦܐ',

# Namespace 8 related
'allmessages'               => 'ܐܓܪ̈ܬܐ ܕܛܟܣܐ',
'allmessagesname'           => 'ܫܡܐ',
'allmessages-filter-all'    => 'ܟܠ',
'allmessages-language'      => 'ܠܫܢܐ:',
'allmessages-filter-submit' => 'ܙܠ',

# Thumbnails
'thumbnail-more'  => 'ܐܘܪܒ',
'thumbnail_error' => 'ܦܘܕܐ ܒܒܪܝܐ ܕܨܘܪܬܐ ܙܥܘܪܬܐ: $1',

# Special:Import
'import'                  => 'ܐܥܠ ܦܐܬܬ̈ܐ',
'import-interwiki-submit' => 'ܐܥܠ',
'import-upload-filename'  => 'ܫܡܐ ܕܠܦܦܐ:',
'import-revision-count'   => '$1 {{PLURAL:$1|ܬܢܝܬܐ |ܬܢܝܬ̈ܐ}}',
'importnopages'           => 'ܠܝܬ ܦܐܬܬ̈ܐ ܠܡܥܠܢܘܬܐ.',
'import-noarticle'        => 'ܠܝܬ ܦܐܬܬ̈ܐ ܠܡܥܠܢܘܬܐ!',
'import-upload'           => 'ܐܣܩ ܓܠܝܬ̈ܐ  ܕ XML',

# Import log
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|ܬܢܝܬܐ |ܬܢܝܬ̈ܐ}}',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|ܬܢܝܬܐ |ܬܢܝܬ̈ܐ}} ܡܢ $2',

# Tooltip help for the actions
'tooltip-pt-userpage' => '',
'tooltip-pt-mytalk'   => '',
'tooltip-search'      => 'Search {{SITENAME}}',
'tooltip-t-print'     => 'Printable version of this page',

# Attribution
'anonymous'   => '{{PLURAL:$1|ܡܦܠܚܢܐ ܠܐ ܝܕܝܥܐ|ܡܦܠܚܢ̈ܐ ܠܐ ܝܕ̈ܝܥܐ}} ܕ {{SITENAME}}',
'siteuser'    => '{{SITENAME}} ܡܦܠܚܢܐ $1',
'others'      => 'ܐܚܪ̈ܢܐ',
'siteusers'   => '{{SITENAME}} {{PLURAL:$2|ܡܦܠܚܢܐ|ܡܦܠܚܢ̈ܐ}} $1',
'creditspage' => 'ܙܕ̈ܩܐ ܕܦܐܬܐ',

# Info page
'infosubtitle'   => 'ܝܕ̈ܥܬܐ ܕܦܐܬܐ',
'numedits'       => 'ܡܢܝܢܐ ܕܫܘܚܠܦ̈ܐ (ܦܐܬܐ): $1',
'numtalkedits'   => 'ܡܢܝܢܐ ܕܫܘܚܠܦ̈ܐ (ܦܐܬܐ  ܕܕܘܪܫܐ): $1',
'numwatchers'    => 'ܡܢܝܢܐ ܕܪ̈ܗܝܐ: $1',
'numauthors'     => 'ܡܢܝܢܐ ܕܡܫܚܠܦܢ̈ܐ (ܦܐܬܐ): $1',
'numtalkauthors' => 'ܡܢܝܢܐ ܕܡܫܚܠܦܢ̈ܐ (ܦܐܬܐ ܕܕܘܪܫܐ): $1',

# Math errors
'math_unknown_error' => 'ܦܘܕܐ ܠܐ ܝܕܝܥܐ',

# Patrol log
'patrol-log-auto' => '(ܝܬܐܝܬ)',
'patrol-log-diff' => 'ܬܢܝܬܐ $1',

# Image deletion
'filedeleteerror-short' => 'ܦܘܕܐ ܒܫܝܦܐ ܕܠܦܦܐ: $1',

# Browsing diffs
'previousdiff' => '← ܫܘܚܠܦܐ ܥܬܝܩܐ',
'nextdiff'     => 'ܫܘܚܠܦܐ ܚܕܬܐ →',

# Special:NewFiles
'newimages'       => 'ܒܝܬ ܓܠܚܐ ܕܠܦܦ̈ܐ ܚܕܬ̈ܐ',
'newimages-label' => 'ܫܡܐ ܕܠܦܦܐ (ܐܘ ܡܢܬܐ ܡܢܗ)',
'showhidebots'    => '($1 ܒܘܬ̈ܐ)',
'noimages'        => 'ܠܝܬ ܡܕܡ ܠܚܙܝܐ.',
'ilsubmit'        => 'ܒܨܝ',
'bydate'          => 'ܒܣܝܩܘܡܐ',

# Metadata
'metadata' => 'ܓܠܝܬ̈ܐ ܕܡܝܛܐ',

# EXIF tags
'exif-imagewidth'          => 'ܦܬܘܐ',
'exif-imagelength'         => 'ܐܘܪܟܐ',
'exif-artist'              => 'ܣܝܘܡܐ',
'exif-exposuretime-format' => '$1 ܪܦܦܐ ($2)',
'exif-filesource'          => 'ܥܩܪܐ ܕܠܦܦܐ',
'exif-gpsspeedref'         => 'ܚܕܝܘܬܐ ܕܩܠܘܠܘܬܐ',
'exif-gpstrack'            => 'ܨܘܒܐ ܕܫܘܢܝܐ',
'exif-gpsimgdirectionref'  => 'ܓܒܝܬܐ ܕܨܘܒܐ ܕܫܘܢܝܐ',
'exif-gpsimgdirection'     => 'ܨܘܒܐ ܕܨܘܪܬܐ',

'exif-unknowndate' => 'ܣܝܩܘܡܐ ܠܐ ܝܕܝܥܐ',

'exif-orientation-1' => 'ܟܝܢܝܐ',

'exif-exposureprogram-1' => 'ܐܝܕܝܐ',
'exif-exposureprogram-2' => 'ܬܚܪܙܬܐ ܟܝܢܝܬܐ',

'exif-meteringmode-0'   => 'ܠܐ ܝܕܝܥܐ',
'exif-meteringmode-255' => 'ܐܚܪܢܐ',

'exif-lightsource-0'  => 'ܠܐ ܝܕܝܥܐ',
'exif-lightsource-9'  => 'ܨܚܘܐ',
'exif-lightsource-10' => 'ܐܬܝܪܐ ܥܝܒܝܐ',
'exif-lightsource-11' => 'ܛܠܐ',

'exif-customrendered-0' => 'ܥܡܠܝܬܐ ܟܝܢܝܬܐ',

'exif-contrast-0' => 'ܟܝܢܝܐ',
'exif-contrast-1' => 'ܪܟܝܟܐ',
'exif-contrast-2' => 'ܩܫܝܐ',

'exif-saturation-0' => 'ܟܝܢܝܐ',

'exif-sharpness-0' => 'ܟܝܢܝܐ',
'exif-sharpness-1' => 'ܪܟܝܟܐ',
'exif-sharpness-2' => 'ܩܫܝܐ',

'exif-subjectdistancerange-0' => 'ܠܐ ܝܕܝܥܐ',
'exif-subjectdistancerange-2' => 'ܚܝܪܐ ܩܪܝܒܐ',
'exif-subjectdistancerange-3' => 'ܚܝܪܐ ܪܚܘܩܐ',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'ܟܝܠܘܡܬܪ ܒܫܥܬܐ',
'exif-gpsspeed-m' => 'ܡܝܠܐ ܒܫܥܬܐ',
'exif-gpsspeed-n' => 'ܩܛܪ̈ܐ',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'ܨܘܒܐ ܬܪܝܨܐ',
'exif-gpsdirection-m' => 'ܨܘܒܐ ܡܓܢܛܝܣܝܐ',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ܟܠ',
'imagelistall'     => 'ܟܠ',
'watchlistall2'    => 'ܟܠ',
'namespacesall'    => 'ܟܠ',
'monthsall'        => 'ܟܠ',
'limitall'         => 'ܟܠ',

# E-mail address confirmation
'confirmemail'             => 'ܚܬܬ ܒܝܠܕܪܐ ܐܠܩܛܪܘܢܝܐ',
'confirmemail_subject'     => 'ܚܘܬܬܐ ܕܒܝܠܕܪܐ ܐܠܩܛܪܘܢܝܐ ܡܢ {{SITENAME}}',
'confirmemail_invalidated' => 'ܚܘܬܬܐ ܕܒܝܠܕܪܐ ܐܠܩܛܪܘܢܝܐ ܒܛܠ',
'invalidateemail'          => 'ܒܛܘܠ ܚܘܬܬܐ ܕܒܝܠܕܪܐ ܐܠܩܛܪܘܢܝܐ',

# Trackbacks
'trackbackremove' => '([$1 ܫܘܦ])',

# Delete conflict
'recreate' => 'ܒܪܝ ܙܒܢܬܐ ܐܚܪܬܐ',

# action=purge
'confirm_purge_button' => 'ܛܒ',

# Multipage image navigation
'imgmultipageprev' => '← ܫܘܚܠܦܐ ܩܕܝܡܐ',
'imgmultipagenext' => '← ܫܘܚܠܦܐ ܚܕܬܐ',
'imgmultigo'       => 'ܙܠ!',
'imgmultigoto'     => 'ܙܠ ܠܦܐܬܐ $1',

# Table pager
'ascending_abbrev'         => 'ܡܣܩܐܝܬ',
'descending_abbrev'        => 'ܡܚܬܐܝܬ',
'table_pager_next'         => 'ܦܐܬܐ ܐܚܪܬܐ',
'table_pager_prev'         => 'ܦܐܬܐ ܩܕܝܡܬܐ',
'table_pager_first'        => 'ܦܐܬܐ ܩܕܡܝܬܐ',
'table_pager_last'         => 'ܦܐܬܐ ܐܚܪܝܬܐ',
'table_pager_limit_submit' => 'ܙܠ',
'table_pager_empty'        => 'ܠܝܬ ܦܠܛ̈ܐ',

# Auto-summaries
'autosumm-blank'   => 'ܐܣܦܩ ܦܐܬܐ',
'autoredircomment' => 'ܦܐܬܐ ܨܝܒܬ ܠ [[$1]]',
'autosumm-new'     => "ܒܪܐ ܦܐܬܐ ܥܡ '$1'",

# Watchlist editor
'watchlistedit-normal-title' => 'ܫܚܠܦ ܪ̈ܗܝܬܐ',
'watchlistedit-raw-submit'   => 'ܚܕܬ ܪ̈ܗܝܬܐ',

# Watchlist editing tools
'watchlisttools-view' => 'ܚܘܝ ܫܘܚܠܦ̈ܐ ܕ̈ܡܝܐ',
'watchlisttools-edit' => 'ܚܙܝ ܘܫܚܠܦ ܪ̈ܗܝܬܐ',

# Special:Version
'version-specialpages' => 'ܦܐܬܬ̈ܐ ܕ̈ܝܠܢܝܬܐ',
'version-other'        => 'ܐܚܪܢܐ',

# Special:FilePath
'filepath'        => 'ܫܒܝܠܐ ܕܠܦܦܐ',
'filepath-page'   => 'ܠܦܦܐ',
'filepath-submit' => 'ܫܒܝܠܐ',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'ܫܡܐ ܕܠܦܦܐ:',
'fileduplicatesearch-submit'   => 'ܒܨܝ',

# Special:SpecialPages
'specialpages'                 => 'ܦܐܬܬ̈ܐ ܕ̈ܝܠܢܝܬܐ',
'specialpages-group-other'     => 'ܦܐܬܬ̈ܐ ܕ̈ܝܠܢܝܬܐ ܐܚܪ̈ܢܝܬܐ',
'specialpages-group-login'     => 'ܥܘܠ / ܒܪܝ',
'specialpages-group-changes'   => 'ܫܘܚܠܦ̈ܐ ܚܕ̈ܬܐ ܘܣܓܠ̈ܐ',
'specialpages-group-users'     => 'ܡܦܠܚܢ̈ܐ ܘܙܕ̈ܩܐ',
'specialpages-group-pages'     => 'ܒܪ̈ܒܝܢ ܕܦܐܬܬ̈ܐ',
'specialpages-group-pagetools' => 'ܡܐܢ̈ܐ ܕܦܐܬܐ',
'specialpages-group-wiki'      => 'ܓܠܝܬ̈ܐ ܘܡܐܢ̈ܐ ܕܘܝܩܝ',
'specialpages-group-redirects' => 'ܨܘܝܒܐ ܕܦܐܬܐ ܕܝܠܢܝܬܐ',

# Special:BlankPage
'blankpage' => 'ܦܐܬܐ ܣܦܝܩܬܐ',

# Special:Tags
'tags-edit'     => 'ܫܚܠܦ',
'tags-hitcount' => '$1 {{PLURAL:$1|ܫܘܚܠܦܐ|ܫܘܚܠܦ̈ܐ}}',

# HTML forms
'htmlform-submit'              => 'ܫܕܪ',
'htmlform-reset'               => 'ܠܐ ܥܒܘܕ ܫܘܚܠܦ̈ܐ',
'htmlform-selectorother-other' => 'ܐܚܪܢܐ',

# Add categories per AJAX
'ajax-add-category'            => 'ܐܘܣܦ ܣܕܪܐ',
'ajax-add-category-submit'     => 'ܐܘܣܦ',
'ajax-confirm-save'            => 'ܢܛܘܪ',
'ajax-remove-category-summary' => 'ܠܚܝ ܣܕܪܐ "$1"',
'ajax-error-title'             => 'ܦܘܕܐ',
'ajax-error-dismiss'           => 'ܛܒ',

);
