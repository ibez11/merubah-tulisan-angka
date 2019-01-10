<?php

        $val = '';
    if(isset($_POST['submit']))
    {
        // $val_entered = str_replace(',','',$_POST['nmbr']);
        // $num_list = explode('.',$val_entered);
        // $full_val = convert_num($num_list[0]);$cnxn = '';
        // if($num_list[0]!=0){$cnxn = ' and ';}

        // $dec_val = convert_num($num_list[1]);
        // $val=' Value entered  is '.$val_entered.' =  Rupees '.$full_val.$cnxn.' cents '.$dec_val;

        $full_val = convert_num(strtolower($_POST['nmbr']));
        print_r($full_val);

    }
?>
<form name="cnvt" id="cnvt" action="" method="post">
<input type="text" id="nmbr" name="nmbr" value="seratus ribu" />
<input type="submit" id="submit" name="submit" value="Submit"  />
</form>
<?php print  $val ?>

<?php
function convert_num($val)
{
    
    $val = strtr(
        $val,
        array(
            'nol'      => '0',
            'se'         => '1',
            'satu'       => '1',
            'dua'       => '2',
            'tiga'     => '3',
            'empat'      => '4',
            'lima'      => '5',
            'enam'       => '6',
            'tujuh'     => '7',
            'delapan'     => '8',
            'sembilan'      => '9',
            'sepuluh'       => '10',
            'sebelas'    => '11',
            'dua belas'    => '12',
            'tiga belas'  => '13',
            'empat belas'  => '14',
            'lima belas'   => '15',
            'enam belas'   => '16',
            'tujuh belas' => '17',
            'delapan belas'  => '18',
            'sembilan belas'  => '19',
            'dua puluh'    => '20',
            'tiga puluh'    => '30',
            'empat puluh'     => '40',
            'lima puluh'     => '50',
            'enam puluh'     => '60',
            'tujuh puluh'   => '70',
            'delapan puluh'    => '80',
            'sembilan puluh'    => '90',
            'ratus'   => '100',
			'seratus'   => '100',
            'ribu'  => '1000',
			'seribu'  => '1000',
            'juta'   => '1000000',
        )
    );

        $parts = array_map(
        function ($val) {
            return floatval($val);
        },
        preg_split('/[\s-]+/', $val)
    );
    
    

    $stack = new SplStack; 
    $sum   = 0;
    $last  = null;
    $check = 1;

    foreach ($parts as $part) {
        
        
            if (!$stack->isEmpty()) {
                if($part >= 1) {
                    if ($stack->top() > $part) {
                        if ($last >= 1000) {
                            $b = spelled_out($part);
                            // print_r(strpos($b,'belas'));
                            if(strpos($b,'belas') !== false ) {
                                $check = 0;
                            } else {
                                $sum += $stack->pop();
                                $stack->push($part);

                            }
                            // $sum += $stack->pop();
                            // $stack->push($part);
                            
                            
                        } else {
                            $stack->push($stack->pop() + $part);
                        }
                        
                    } else {
                        $stack->push($stack->pop() * $part);
                    }
                } else {
                    $check = 0;
                }
            } else {
                $stack->push($part);
            }
            $last = $part;
        
        
        // if($last < 1) {

        // }
        
        
    }
    
    
    if($check == 0) {
        return 'invalid';
    } else {
        return number_format($sum + $stack->pop());
    }
    

}



function spelled_out($n) {
    $dasar = array(1=>'satu','dua','tiga','empat','lima','enam','tujuh','delapan','sembilan');
    $angka = array(1000000000,1000000,1000,100,10,1);
    $satuan = array('milyar','juta','ribu','ratus','puluh','');
    $i=0;
    $str="";
    while($n!=0){
            $count = (int)($n/$angka[$i]);
            if($count>=10) $str .= $eja($count). " ".$satuan[$i]." ";
            else if($count > 0 && $count < 10)
                    $str .= $dasar[$count] . " ".$satuan[$i]." ";
            $n -= $angka[$i] * $count;
            $i++;
    }
    $str = preg_replace("/satu puluh (\w+)/i","\\1 belas",$str);
    $str = preg_replace("/satu (ribu|ratus|puluh|belas)/i","se\\1",$str);
    return $str;
}


?>