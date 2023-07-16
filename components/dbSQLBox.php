<?php
    if(!isset($_GET['db']) || !isset($_SESSION['username'])) {
        die("error");
    }
?>

<script>
    function confirmSQLAction() {
        document.getElementById('sqlmodalbg').classList.remove('d-none');
        document.getElementById('sqlquerymodal').classList.remove('d-none');
    }

    function performProvidedSQLAction(a) {
        document.getElementById('sqlmodalbg').classList.add('d-none');
        document.getElementById('sqlquerymodal').classList.add('d-none');
        if(a == true) {
            document.getElementById('sqlresult').classList.remove('d-none');
            document.getElementById('sqlresult').src = "../components/sqlQueryerizor.php?sqlQuery=" + document.getElementById('sqlQueryTxt').innerText + "&db=<?php echo $_GET['db']; ?>";
        }
    }
</script>

<div class="db-box">
    <h1 class="spacing-0">SQL</h1>
    <div>
        <form action="../components/sqlQueryerizor.php" method="get">
            <div class="d-flex gap-5">
                <input type="hidden" name="db" value="<?php echo $_GET['db']; ?>">
                <textarea name="sqlQuery" id="sqlQueryBox" rows="5" placeholder="SELECT * FROM <?php echo isset($_GET['table']) ? base64_decode($_GET['table']) : "table_name"; ?>" oninput="document.getElementById('sqlQueryTxt').innerText = this.value;"></textarea>
                <div style="width: 30%; align-self: end;">
                    <button type="reset" class="btn btn-secondary width-full mb-5">Reset</button>
                    <button type="button" class="btn btn-primary width-full" onclick="confirmSQLAction();">Go</button>
                </div>
            </div>

            <div class="modal modal-default d-none" id="sqlquerymodal">
                <div class="modalContents text-center">
                    <p><b>Are you sure you would like to complete this query?</b></p>
                    <p>Do you really want to perform <code id="sqlQueryTxt" class="code"></code>?</p>
                    <button class="btn btn-primary" type="button" onclick="performProvidedSQLAction(true);">Yes</button>
                    <button class="btn btn-secondary" type="button" onclick="performProvidedSQLAction(false);">Nevermind</button>
                </div>
            </div>
            <div class="modalbg d-none" id="sqlmodalbg"></div>
        </form>

        <iframe src="https://github.com/ethrythedev/FlowSQL/" height="100px" class="d-none width-full mt-5" id="sqlresult">
    </div>
</div>