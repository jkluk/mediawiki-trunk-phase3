<?php
/**
 * Stub profiling functions
 * @file
 * @ingroup Profiler
 */
if ( !class_exists( 'Profiler' ) ) {
	require_once( dirname( __FILE__ ) . '/Profiler.php' );
}

class ProfilerStub extends Profiler {

	/**
	 * is setproctitle function available?
	 * @var bool
	 */
	private $haveProctitle;
	private $hackWhere = array();

	/**
	 * Constructor. Check for proctitle.
	 */
	public function __construct() {
		$this->haveProctitle = function_exists( 'setproctitle' );
	}

	/**
	 * Begin profiling of a function
	 * @param $fn string
	 */
	public function profileIn( $fn = '' ) {
		global $wgDBname;
		if( $this->haveProctitle ){
			$this->hackWhere[] = $fn;
			setproctitle( $fn . " [$wgDBname]" );
		}
	}

	/**
	 * Stop profiling of a function
	 * @param $fn string
	 */
	public function profileOut( $fn = '' ) {
		global $wgDBname;
		if( !$this->haveProctitle ) {
			return;
		}
		if( count( $this->hackWhere ) ) {
			array_pop( $this->hackWhere );
		}
		if( count( $this->hackWhere ) ) {
			setproctitle( $this->hackWhere[count( $this->hackWhere )-1] . " [$wgDBname]" );
		}
	}

	/**
	 * Does nothing, just for compatibility
	 */
	public function getOutput() {}
	public function close() {}
}

/** backward compatibility */
$wgProfiling = false;
$wgProfiler = new ProfilerStub();
