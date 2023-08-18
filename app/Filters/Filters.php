<?php

namespace App\Filters;
use Illuminate\Http\Request;

abstract class Filters
{

    protected $request, $builder;
    protected $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    //apply filters to a query builder
    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value)
        {   // check if method by() exist comparing with by filter
            if (method_exists($this, $filter))
            {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }
    // get filters from request
    public function getFilters()
    {
        return $this->intersect($this->filters);
    }
    // intersect is removed from 5.4 and this is the way to do it
    //EMPTY ARRAY IF VALUE IS NOT PASS TO EXISTING KEY by= return []
    protected function intersect($keys) //helper function
    {
        return array_filter($this->request->only(is_array($keys) ? $keys : func_get_args()));
    }
}   