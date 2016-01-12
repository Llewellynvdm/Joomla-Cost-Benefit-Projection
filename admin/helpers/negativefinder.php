<?php
/**
* 
* 	@version 	1.0.0
* 	@package 	Detecting negative numbers
* 	@license	GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
*
**/

// No direct access.
defined('_JEXEC') or die;

/**
*	Detecting negative numbers
**/

class Expression
{
    protected $expression;
    protected $result;

    public function __construct($expression)
	{
        $this->expression = $expression;
    }

    public function evaluate()
	{
        $this->result = eval("return ".$this->expression.";");
        return $this;
    }

    public function getResult()
	{
        return $this->result;
    }
}

class NegativeFinder
{
    protected $expressionObj;

    public function __construct(Expression $expressionObj)
	{
        $this->expressionObj = $expressionObj;
    }


    public function isItNegative()
	{
        $result = $this->expressionObj->evaluate()->getResult();

        if($this->hasMinusSign($result))
		{
            return true;
        }
		else
		{
            return false;
        }
    }

    protected function hasMinusSign($value) 
	{
        return (substr(strval($value), 0, 1) == "-");
    }
}
