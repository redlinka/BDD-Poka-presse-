<?php

include 'includes/cnx.php';

include 'includes/header.php';

// check si l'utilisateur a les droits d'accès
if (!isset($_SESSION['login']) || $_SESSION['fonction'] != 'AD' && $_SESSION['fonction'] != 'CR') {
    http_response_code(403);
    echo '<div style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; display: flex; justify-content: center; align-items: center;">Vous n\'avez pas le droit d\'être ici.</div>';
    exit;
}
?>
<h1>Articles de Poka Presse</h1>
<div class="horizontal">
    <div class="vertical">
        <iframe class="frame" id="articles" src="liste_articles.php"></iframe>
    </div>
    <div class="vertical">
        <iframe id="details"></iframe>
    </div>
</div>
<script>
    document.getElementById('articles').addEventListener('load', function() {
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
<?php