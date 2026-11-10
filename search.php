<?php include_once('./recep-muestras.php');
?>
<section class="container__sup">                        
            <form enctype="multipart/form-data" action="./recep-muestras.php" method="POST" name="formsearch">
                <div class="search-inform" style=" width: 100%;">
                    <input type="text" class="form-control" value="" name="noinforme" id="nInforme" placeholder="Numero de informe">                    
                    <div class="btn-search pull-right" style="margin-top:10px;">
                        <input type="submit" class="btn btn-primary" value="Buscar">
                        <input type="hidden" name="searchinform" value="formsearch">
                    </div>                   
                </div>
            </form>            
</section>