<?PHP
/**
 * paginator.php
 *
 * longdesc
 *
 * LICENSE: This source file is subject to version 4.0 of the Creative Commons
 * license that is available through the world-wide-web at the following URI:
 * https://creativecommons.org/licenses/by/4.0/
 *
 * @category   Geochronology
 * @package    Geochron Portal
 * @author     Jason Ash <jasonash@ku.edu>
 * @copyright  IEDA (http://www.iedadata.org/)
 * @license    https://creativecommons.org/licenses/by/4.0/  Creative Commons License 4.0
 * @version    GitHub: $
 * @link       http://www.geochron.org
 * @see        Geochron, Geochronology
 */


/*
foreach($_SERVER as $key=>$value){
	echo "$key : $value <br>";
}
*/

class Paginator{  
	var $items_per_page;  
	var $items_total;  
	var $current_page;  
	var $num_pages;  
	var $mid_range;  
	var $low;  
	var $high;  
	var $limit;  
	var $return;  
	var $default_ipp = 8;
	
	var $thisuri;
	

  
	function Paginator()  
	{  
		$this->current_page = 1;  
		$this->mid_range = 7;  
		$this->items_per_page = (!empty($_GET['ipp'])) ? $_GET['ipp']:$this->default_ipp;  

	}  
  
	function paginate()  
	{

		$thisuri=$_SERVER[PHP_SELF]."?";
		
		foreach($_GET as $key=>$value){
			if($key!="page" && $key!="ipp"){
				if($key!="" && $value!=""){
					$thisuri.="&$key=$value";
				}
			}
		}

		if($_GET['ipp'] == 'All')  
		{  
			$this->num_pages = ceil($this->items_total/$this->default_ipp);  
			$this->items_per_page = $this->default_ipp;  
		}  
		else  
		{  
			if(!is_numeric($this->items_per_page) OR $this->items_per_page <= 0) $this->items_per_page = $this->default_ipp;  
			$this->num_pages = ceil($this->items_total/$this->items_per_page);  
		}  
		$this->current_page = (int) $_GET['page']; // must be numeric > 0  
		if($this->current_page < 1 Or !is_numeric($this->current_page)) $this->current_page = 1;  
		if($this->current_page > $this->num_pages) $this->current_page = $this->num_pages;  
		$prev_page = $this->current_page-1;  
		$next_page = $this->current_page+1;  
  
		if($this->num_pages > 10)  
		{  
			$this->return = ($this->current_page != 1 And $this->items_total >= 10) ? "<a class=\"paginate\" href=\"".$thisuri."&page=$prev_page&ipp=$this->items_per_page\">Previous</a> ":"<span class=\"inactive\" >Previous</span> ";  
  
			$this->start_range = $this->current_page - floor($this->mid_range/2);  
			$this->end_range = $this->current_page + floor($this->mid_range/2);  
  
			if($this->start_range <= 0)  
			{  
				$this->end_range += abs($this->start_range)+1;  
				$this->start_range = 1;  
			}  
			if($this->end_range > $this->num_pages)  
			{  
				$this->start_range -= $this->end_range-$this->num_pages;  
				$this->end_range = $this->num_pages;  
			}  
			$this->range = range($this->start_range,$this->end_range);  
  
			for($i=1;$i<=$this->num_pages;$i++)  
			{  
				if($this->range[0] > 2 And $i == $this->range[0]) $this->return .= " ... ";  
				// loop through all pages. if first, last, or in range, display  
				if($i==1 Or $i==$this->num_pages Or in_array($i,$this->range))  
				{  
					$this->return .= ($i == $this->current_page And $_GET['page'] != 'All') ? "<a title=\"Go to page $i of $this->num_pages\" class=\"current\" >$i</a> ":"<a class=\"paginate\" title=\"Go to page $i of $this->num_pages\" href=\"".$thisuri."&page=$i&ipp=$this->items_per_page\">$i</a> ";  
				}  
				if($this->range[$this->mid_range-1] < $this->num_pages-1 And $i == $this->range[$this->mid_range-1]) $this->return .= " ... ";  
			}  
			$this->return .= (($this->current_page != $this->num_pages And $this->items_total >= 10) And ($_GET['page'] != 'All')) ? "<a class=\"paginate\" href=\"".$thisuri."&page=$next_page&ipp=$this->items_per_page\">Next</a>\n":"<span class=\"inactive\" >Next</span>\n";  
			//$this->return .= ($_GET['page'] == 'All') ? "<a class=\"current\" style=\"margin-left:10px\" href=\"#\">All</a> \n":"<a class=\"paginate\" style=\"margin-left:10px\" href=\"".$thisuri."&page=1&ipp=All\">All</a> \n";  
		}  
		else  
		{  
			for($i=1;$i<=$this->num_pages;$i++)  
			{  
				$this->return .= ($i == $this->current_page) ? "<a class=\"current\" >$i</a> ":"<a class=\"paginate\" href=\"".$thisuri."&page=$i&ipp=$this->items_per_page\">$i</a> ";  
			}  
			//$this->return .= "<a class=\"paginate\" href=\"".$thisuri."&page=1&ipp=All\">All</a> \n";  
		}  
		$this->low = ($this->current_page-1) * $this->items_per_page;  
		$this->high = ($_GET['ipp'] == 'All') ? $this->items_total:($this->current_page * $this->items_per_page)-1;  
		$this->limit = ($_GET['ipp'] == 'All') ? "":" LIMIT $this->low,$this->items_per_page";  
	}  
  
	function display_items_per_page()  
	{ 
		$thisuri=$_SERVER[PHP_SELF]."?";
		
		foreach($_GET as $key=>$value){
			if($key!="page" && $key!="ipp"){
				if($key!="" && $value!=""){
					$thisuri.="&$key=$value";
				}
			}
		}
		
		$items = '';  
		$ipp_array = array(8,15,25);  //array(10,25,50,100,'All')
		foreach($ipp_array as $ipp_opt)    $items .= ($ipp_opt == $this->items_per_page) ? "<option selected value=\"$ipp_opt\">$ipp_opt</option>\n":"<option value=\"$ipp_opt\">$ipp_opt</option>\n";  
		return "<span class=\"apaginate\">Items per page:</span>&nbsp;<select class=\"apaginate\" onchange=\"window.location='".$thisuri."&page=1&ipp='+this[this.selectedIndex].value;return false\">$items</select>\n";  
	}  
  
	function display_jump_menu()  
	{ 
		$thisuri=$_SERVER[PHP_SELF]."?";
		
		foreach($_GET as $key=>$value){
			if($key!="page" && $key!="ipp"){
				if($key!="" && $value!=""){
					$thisuri.="&$key=$value";
				}
			}
		}
		
		for($i=1;$i<=$this->num_pages;$i++)  
		{  
			$option .= ($i==$this->current_page) ? "<option value=\"$i\" selected>$i</option>\n":"<option value=\"$i\">$i</option>\n";  
		}  
		return "<span class=\"apaginate\">Page:&nbsp;</span><select class=\"apaginate\" onchange=\"window.location='".$thisuri."&page='+this[this.selectedIndex].value+'&ipp=$this->items_per_page';return false\">$option</select>\n";  
	}  
  
	function display_pages()  
	{  
		return $this->return;  
	}  
}  
?>