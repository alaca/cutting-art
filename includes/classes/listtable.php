<?php
/**
* @package     PriorityAPI
* @author      Ante Laca <ante.laca@gmail.com>
* @copyright   2018 Roi Holdings
*/

namespace CuttingArt;


class ListTable extends \WP_List_Table
{

    private $per_page = 10;
    private $filters = [];
    private $columns = [];
    private $hidden_columns = [];
    private $sortable_columns = [];

    public function __construct()
    {
        parent::__construct();
    }
    

    /**
     * Show table
     *
     * @return void
     */
    public function show($data)
    {
        $per_page = $this->get_perpage();
        $current_page = $this->get_pagenum();
        $total = count($data);

        $this->set_pagination_args([
            'total_items' => $total,
            'per_page'    => $per_page
        ]);

        $data = array_slice($data, (($current_page - 1) * $per_page), $per_page);

        $this->_column_headers = [
            $this->get_columns(), 
            $this->get_hidden_columns(), 
            $this->get_sortable_columns()
        ];

        $this->items = $data;

        return $this->display();
    
    }


    /**
     * Set columns
     *
     * @param [array] $columns
     * @return ListTable
     */
    public function columns($columns)
    {
        $this->columns = $columns;
        return $this;
    }

    
    /**
     * Set hidden columns
     *
     * @param [array] $columns
     * @return ListTable
     */
    public function hidden_columns($columns)
    {
        $this->hidden_columns = $columns;
        return $this;
    }

    
    /**
     * Set sortable columns
     *
     * @param [array] $columns
     * @return ListTable
     */
    public function sortable_columns($columns)
    {
        $this->sortable_columns = $columns;
        return $this;
    }

    /**
     * Set items per page number
     *
     * @param [int] $num
     * @return ListTable
     */
    public function per_page($num)
    {
        $this->per_page = intval($num);
        return $this;
    }


 
    /**
     * Get columns
     *
     * @return array
     */
    public function get_columns()
    {
        return $this->columns;
    }

     
    /**
     * Get columns
     *
     * @return array
     */
    public function get_perpage()
    {
        return $this->per_page;
    }


    /**
     * Get hidden columns
     *
     * @return array
     */
    public function get_hidden_columns()
    {
        return $this->hidden_columns;
    }


    /**
     * Get sortable columns
     *
     * @return array
     */
    public function get_sortable_columns()
    {
        return $this->sortable_columns;
    }

    /**
     * Filter column
     *
     * @param [mixed] $name
     * @param [callable] $callback
     * @return ListTable
     */
    public function filter($name, $callback)
    {
        if (is_array($name)) {

            foreach($name as $column) {
                $this->filters[$column] = $callback;
            }

        } else {
            $this->filters[$name] = $callback;
        }
        
        return $this;
    }


    /**
     * Show column
     *
     * @param [string] $item
     * @param [string] $name
     */
    public function column_default($item, $name)
    {
        if (isset($this->filters[$name])) {
            return call_user_func_array($this->filters[$name], [$item, $name]);
        }

        if (isset($item[$name])) {
            return $item[$name];
        }

    }

}
