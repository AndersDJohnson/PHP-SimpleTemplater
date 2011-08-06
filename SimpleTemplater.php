<?
/**
 * Simple Templater class
 *  Copyright August 2011 by Anders D. Johnson
 * 
 * Give it template strings with keys from the given $data array enclosed in double mustaches, i.e. {{key}} to have it replace them with values;
 * 
 */
class SimpleTemplater {
	
	private $_data;
	private $_template;
	
	public function __construct ( $template='', $data=array() ) {
		$this->_template = $template;
		$this->_data = $data;
	}
	
	public function __get( $key ) {
		switch ($key) {
			//private read
			case 'data':
				return;
			//public read
			default:
				return $this->{"_$key"};
		}
	}
	
	public function __set( $key, $value ) {
		switch ($key) {
			//private write
			case '':
				break;
			//public write
			default:
				$this->{"_$key"} = $value;
		}
	}
	
	public function render() {
		
		$rendering = $this->_template;
		
		// First do regular variable replacements.
		$matches = array();
		preg_match_all( '/{{([^:{}]+).*?}}/', $rendering, $matches );
		
		$matched = array_slice($matches, 1);
		$matched = $matched[0];
		
		$rendering = $this->_template;
		foreach ($matched as $i => $name) {
			$value = $this->_data[$name];
			$rendering = preg_replace( '/{{'.$name.'}}/', $value, $rendering );
		}
		
		// Now process conditionals (if a variable key is defined).
		$matches = array();
		preg_match_all( '/{{:if:([^{}]+)}}(.*?){{:endif}}/', $rendering, $matches );
		$matched = array_slice($matches, 1);
		$varNames = $matched[0];
		$conditionals = $matched[1];
		
		foreach ($varNames as $i => $name) {
			if ( isset($this->_data[$name]) ) {
				$value = $conditionals[$i];
			} else {
				$value = '';
			}
			$rendering = preg_replace( '/{{:if:'.$name.'}}.*?{{:endif}}/', $value, $rendering );
		}
		
		return $rendering;
	}
}
?>
