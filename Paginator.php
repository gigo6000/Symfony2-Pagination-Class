<?php
/**
 * Class to paginate a list of items in a old digg style 
 *
 * @author Darko Goleš
 * @author Carlos Mafla <gigo6000@hotmail.com> 
 * @www.inchoo.net
 */
namespace Paginator;

class Paginator {

    //current displayed page
    protected $currentpage;
    //limit items on one page
    protected $limit;
    //total number of pages that will be generated
    protected $numpages;
    //total items loaded from database
    protected $itemscount;
    //starting item number to be shown on page
    protected $offset;
    //pages to show at left and right of current page
    protected $mid_range;
    //range initial page   
    protected $start_range;
    //range end page
    protected $end_range;
 

    function __construct($itemscount, $currentpage = 1,$limit = 20,$mid_range = 7) {
        //set total items count from controller
        $this->itemscount = $itemscount;

        $this->currentpage = $currentpage;

        $this->limit = $limit;

        $this->mid_range= $mid_range;

        //Set defaults
        $this->setDefaults();
        
        //Calculate number of pages total
        $this->getInternalNumPages();
        //Calculate first shown item on current page
        $this->calculateOffset();
        
        
        $this->calculateRange();

    }
   

    private function calculateRange() {

             $this->start_range = $this->currentpage - floor($this->mid_range/2);  
             $this->end_range = $this->currentpage + floor($this->mid_range/2);  

             if($this->start_range <= 0)  
             {  
                 $this->end_range += abs($this->start_range)+1;  
                 $this->start_range = 1;  
             }  
             if($this->end_range > $this->numpages)  
             {  
                 $this->start_range -= $this->end_range-$this->numpages;  
                 $this->end_range = $this->numpages;  
             }  
             $this->range = range($this->start_range,$this->end_range);   


      }

    private function setDefaults() {
        //If currentpage is set to null or is set to 0 or less
        //set it to default (1)
        if (($this->currentpage == null) || ($this->currentpage < 1)) {
            $this->currentpage = 1;
        }
        //if limit is set to null set it to default (20)
        if (($this->limit == null)) {
            $this->limit = 20;
            //if limit is any number less than 1 then set it to 0 for displaying 
            //items without limit
        } else if ($this->limit < 1) {
            $this->limit = 0;
        }
    }
    
    public function getNumpages() {
        return $this->numpages;
    }
    
    private function getInternalNumPages() {
        //If limit is set to 0 or set to number bigger then total items count
        //display all in one page
        if (($this->limit < 1) || ($this->limit > $this->itemscount)) {
            $this->numpages = 1;
        } else {
            //Calculate rest numbers from dividing operation so we can add one 
            //more page for this items
            $restItemsNum = $this->itemscount % $this->limit;
            //if rest items > 0 then add one more page else just divide items 
            //by limit
            $restItemsNum > 0 ? $this->numpages = intval($this->itemscount / $this->limit) + 1 : $this->numpages = intval($this->itemscount / $this->limit);
        }
    }
    

    
    private function calculateOffset() {
        //Calculet offset for items based on current page number
        $this->offset = ($this->currentpage - 1) * $this->limit;
    }
    
    public function getCurrentpage() {
        return $this->currentpage;
    }

    public function getCurrentUrl() {
        return $this->currentUrl;
    }

        //For using from controller
    public function getLimit() {
        return $this->limit;
    }
    //For using from controller
    public function getOffset() {
        return $this->offset;
    }

    public function getRange()
    {
        return $this->range;
    }

    public function getMidRange()
    {
        return $this->mid_range;
    }

}
