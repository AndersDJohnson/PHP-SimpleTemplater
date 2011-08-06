<?
/**
 * Simple Templater class
 *  Copyright August 2011 by Anders D. Johnson
 * 
 * Give it template strings with keys from the given $data array enclosed in double mustaches, i.e. {{key}} to have it replace them with values;
 * 
 */
class SimpleTemplater {
	
	private $data;
	private $template;
	
	public function __construct ( $template='', $data=array() ) {
		$this->template = $template;
		$this->data = $data;
	}
	
	public function setTemplate( $newTemplate = '' ) {
		$this->template = $newTemplate;
	}
	
	public function setData( $newData = array() ) {
		$this->data = $newData;
	}
	
	public function render() {
		
		// First do regular variable replacements.
		$matches = array();
		preg_match_all( '/{{([^:{}]+).*?}}/', $this->template, $matches );
		
		$matched = array_slice($matches, 1);
		$matched = $matched[0];
		
		$rendering = $this->template;
		foreach ($matched as $i => $name) {
			$value = $this->data[$name];
			$rendering = preg_replace( '/{{'.$name.'}}/', $value, $rendering );
		}
		
		// Now process conditionals (if a variable key is defined).
		$matches = array();
		preg_match_all( '/{{:if:([^{}]+)}}(.*?){{:endif}}/', $rendering, $matches );
		$matched = array_slice($matches, 1);
		$varNames = $matched[0];
		$conditionals = $matched[1];
		
		foreach ($varNames as $i => $name) {
			if ( isset($this->data[$name]) ) {
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
