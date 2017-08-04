<?php

/**
* DECADE Pagination Class.
*
* This class creates pagination links
*
* @package    DECADE Application
* @author     Jason Ash <jasonash@ku.edu>
*/


/**
* Pagination Class.
*
* This class creates pagination links
*/
class DecadePagination
{

	/**
	* Constructor
	*
	* @param object $db Database handle
	*
	*/
	public function DecadePagination($pagenum,$totalcount,$link,$perpage){
		$this->pagenum=$pagenum;
		$this->totalcount=$totalcount;
		$this->link=$link;
		$this->perpage=$perpage;

	}



	public function writeLinks(){
	
		/*
		echo "pagenum: $this->pagenum<br>";
		echo "totalcount: $this->totalcount<br>";
		echo "link: $this->link<br>";
		echo "perpage: $this->perpage<br>";
		echo "<br>";
		*/
		
		$numlinks = ceil($this->totalcount/$this->perpage);
		
		//echo "numlinks: $numlinks<br><br>";
		
		if($numlinks >1){
		
			$previouspage = $this->pagenum - 1;
			$nextpage = $this->pagenum + 1;
			
			if($this->pagenum > 1){
				echo "<span class=\"decadepaginate\"><a href=\"javascript:$this->link($previouspage)\" title=\"Go to page $previouspage\">Previous</a></span>";
			}
			
			if($this->pagenum > 6){
				$firstlink = $this->pagenum-4;
			}else{
				$firstlink = 1;
			}
			
			if($firstlink > ($numlinks -8)){
				$firstlink = $numlinks -8;
			}
			
			if($firstlink < 1){
				$firstlink = 1;
			}
			
			if($firstlink!=1){
				echo "<span class=\"decadepaginate\"><a href=\"javascript:$this->link(1)\" title=\"Go to page 1\">1</a></span>";
				echo "<span style=\"font-weight:bold;\">. . .</span>";
			}
			

			
			if($this->pagenum < ($numlinks-5)){
				$lastlink = $this->pagenum+4;
			}else{
				$lastlink = $numlinks;
			}


			if($lastlink < 9){
				$lastlink = 9;
			}
			
			if($lastlink > $numlinks){
				$lastlink = $numlinks;
			}			

			for($x=$firstlink;$x<=$lastlink;$x++){

				if($x==$this->pagenum){
					echo "<span class=\"decadepaginate decadepaginateactive\">$x</span>";
				}else{
					echo "<span class=\"decadepaginate\"><a href=\"javascript:$this->link($x)\" title=\"Go to page $x\">$x</a></span>";
				}
			}
			
			if($lastlink!=$numlinks){
				echo "<span style=\"font-weight:bold;\">. . .</span>";
				echo "<span class=\"decadepaginate\"><a href=\"javascript:$this->link($numlinks)\" title=\"Go to page $numlinks\">$numlinks</a></span>";
			}
			
			if($this->pagenum!=$numlinks){
				echo "<span class=\"decadepaginate\"><a href=\"javascript:$this->link($nextpage)\" title=\"Go to page $nextpage\">Next</a></span>";
			}
		
			echo "<br><br>firstlink: $firstlink lastlink: $lastlink";
		
		}
	
	}

}
?>