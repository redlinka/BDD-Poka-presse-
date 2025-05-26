<?php

include 'includes/cnx.php';

include 'includes/header.php';

?>
<a href="numero.php?code=<?php echo isset($_GET['code']) ? htmlspecialchars($_GET['code']) : ''; ?>" class="btn">Page Hiérarchie</a>

<h1>Maquettes pour le numéro <?php echo isset($_GET['code']) ? htmlspecialchars($_GET['code']) : ''; ?></h1>
<div class="horizontal">
    <div class="vertical">
        <iframe class="frame" id="maquettes" src="liste_maquettes.php?code=<?php echo isset($_GET['code']) ? htmlspecialchars($_GET['code']) : ''; ?>"></iframe>
    </div>
    <div class="horizontal">
        <iframe id="details"></iframe>
    </div>
</div>
<script>
    document.getElementById('maquettes').addEventListener('load', function() {
        try {
            const iframeDoc = this.contentDocument || this.contentWindow.document;
            const links = iframeDoc.querySelectorAll('a');
            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('href');
                    document.getElementById('details').src = url;
                });
                    
                link.setAttribute('target', 'details');
            });
        } catch (error) {}
    });
</script>

<?php include 'includes/footer.php'; ?>