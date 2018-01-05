<?php
class QuadraticEquation{
    private $a;
    private $b;
    private $c;
    private $results=[];
    public function __construct($a,$b,$c)
    {
        $this->a=$a;
        $this->b=$b;
        $this->c=$c;
    }

    /**
     * Hiển thị kết quả từ results
     */
    function display(){
        if(empty($this->results)){
            echo '<p>Phương trình vô nghiệm</p>';
        }else{
            if($this->results['x1']==$this->results['x2']){
                echo '<p>X='.$this->results['x1'].'</p>';
            }else{
                echo '<p>X1='.$this->results['x1'].'</p>';
                echo '<p>X2='.$this->results['x2'].'</p>';
            }
        }

    }

    /**
     * Giải phương trình, nghiệm lưu vào results
     * @return $this
     */
    function solve(){
        if($this->a==0){//a=0 => phương trình bậc nhất
            $this->results['x1']=$this->results['x2']=-$this->c/$this->b;
            return $this;
        }
        $delta=$this->delta();
        if($delta>=0){
            $sqrt_delta=sqrt($delta);
            $this->results['x1']=(-$this->b-$sqrt_delta)/(2*$this->a);
            $this->results['x2']=(-$this->b+$sqrt_delta)/(2*$this->a);
        }
        return $this;
    }
    /**
     * Tính delta
     */
    protected function delta(){
        return $this->b*$this->b-4*$this->a*$this->c;
    }
}
function r($name){
    return isset($_REQUEST[$name])?$_REQUEST[$name]:'';
}
$a=r('a');
$b=r('b');
$c=r('c');
if($a||$b){
    $e=new QuadraticEquation($a,$b,$c);
    $e->solve()->display();
}
?>
<html>
    <meta charset="UTF-8">
    <title>Giải phương trình bậc 2</title>
    <body>
        <form method="get">
            <input name="a" placeholder="a" required type="number" step="0.01">
            <input name="b" placeholder="b" required type="number" step="0.01">
            <input name="c" placeholder="b" required type="number" step="0.01">
            <p>
                <button>Submit</button>
            </p>
        </form>
    </body>
</html>
