<?php

include 'includes/cnx.php';

include 'includes/header.php';

?>

<a href="maquettes.php?code=<?php echo isset($_GET['code']) ? htmlspecialchars($_GET['code']) : ''; ?>" class="btn">Maquettes</a>
<h1>Hierarchie du numéro <?php echo isset($_GET['code']) ? htmlspecialchars($_GET['code']) : ''; ?></h1>
<div class="horizontal">
    <div class="vertical">
        <iframe class="frame" id="hierarchie" src="liste_hierarchie.php?code=<?php echo isset($_GET['code']) ? htmlspecialchars($_GET['code']) : ''; ?>"></iframe>
    </div>
    <div class="vertical">
        <iframe id="details"></iframe>
    </div>
</div>
<?php
$code = isset($_GET['code']) ? htmlspecialchars($_GET['code']) : '';
$showForms = false;
if ($code) {
    $stmt = $cnx->prepare("SELECT date_publication FROM numero WHERE code = :code");
    $stmt->execute(['code' => $code]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && is_null($row['date_publication'])) {
        $showForms = true;
    }
}
if ($showForms):
?>
<form action="numero.php?code=<?php echo $code; ?>" method="post">
    <label for="ajouter_rubrique">Ajouter une rubrique :</label>
    <select name="ajouter_rubrique" id="ajouter_rubrique" required>
        <option value="">Sélectionner une rubrique</option>
        <?php
        $stmt = $cnx->query("SELECT * FROM rubrique WHERE num_rubrique NOT IN (SELECT num_rubrique FROM contient WHERE code = '" . $code . "')");
        $rubriques = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rubriques as $rubrique) {
            echo "<option value='" . htmlspecialchars($rubrique['num_rubrique']) . "'>" . htmlspecialchars($rubrique['nom_rubrique']) . "</option>";
        }
        ?>
    </select>
    <button type="submit" class="btn">Ajouter</button>
</form>
<form action="numero.php?code=<?php echo $code; ?>" method="post">
    <label for="supprimer_rubrique">Supprimer une rubrique :</label>
    <select name="supprimer_rubrique" id="supprimer_rubrique" required>
        <option value="">Sélectionner une rubrique</option>
        <?php
        $stmt = $cnx->query("SELECT * FROM rubrique WHERE num_rubrique IN (SELECT num_rubrique FROM contient WHERE code = '" . $code . "')");
        $rubriques = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rubriques as $rubrique) {
            echo "<option value='" . htmlspecialchars($rubrique['num_rubrique']) . "'>" . htmlspecialchars($rubrique['nom_rubrique']) . "</option>";
        }
        ?>
    </select>
    <button type="submit" class="btn">Supprimer</button>
</form>
<?php endif; ?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['ajouter_rubrique'])) {

        $num_rubrique = (int)$_POST['ajouter_rubrique'];
        $code = htmlspecialchars($_GET['code']);
        $cnx->beginTransaction();
        $cnx->exec("SET TRANSACTION ISOLATION LEVEL SERIALIZABLE");
        $stmt = $cnx->prepare("INSERT INTO contient (code, num_rubrique) VALUES (:code, :num_rubrique)");
        $stmt->execute(['code' => $code, 'num_rubrique' => $num_rubrique]);
        echo "<p>Rubrique ajoutée avec succès.</p>";
        $cnx->commit();
        header("Refresh:0");

    } elseif (isset($_POST['supprimer_rubrique'])) {
        $num_rubrique = (int)$_POST['supprimer_rubrique'];
        $code = htmlspecialchars($_GET['code']);
        $cnx->beginTransaction();
        $cnx->exec("SET TRANSACTION ISOLATION LEVEL SERIALIZABLE");
        $stmt = $cnx->prepare("DELETE FROM contient WHERE code = :code AND num_rubrique = :num_rubrique");
        $stmt->execute(['code' => $code, 'num_rubrique' => $num_rubrique]);
        echo "<p>Rubrique supprimée avec succès.</p>";
        $cnx->commit();
        header("Refresh:0");
    }
}
?>
    
<script>
    document.getElementById('hierarchie').addEventListener('load', function() {
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