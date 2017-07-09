<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Page d'admin de Caconnexion</title>
	<style>
		input {
			display: block;
			margin: 5px 0 5px 0;
		}
		label {
			float: left;
			margin-right: 5px;
		}
	</style>
</head>
<body>
	<h1>Page d'admin</h1>
	<section>
		<h2>Ajouter un fanfaron</h2>
		<form method="post">
			<input type="hidden" name="newone">
			<label for="surnom">Surnom</label>
			<input type="text" name="surnom" id="surnom">
			<label for="email">E-mail</label>
			<input type="email" name="email" id="email">
			<label for="mdp">Mot de passe</label>
			<input type="password" name="mdp" id="mdp">
			<label for="instru">Instrument</label>
			<input type="text" name="instru" id="instru">
			<label for="instru2">Instrument secondaire (si besoin)</label>
			<input type="text" name="instru2" id="instru2">
			<fieldset>
				<legend>Status</legend>
				<label for="pa">1A</label>
				<input type="radio" name="status" value="0" id="pa" checked>
				<label for="actif">Actif</label>
				<input type="radio" name="status" value="1" id="actif">
				<label for="vieux">vieux</label>
				<input type="radio" name="status" value="2" id="vieux">
			</fieldset>
			<label for="generation">Génération</label>
			<input type="number" name="generation" id="generation">
			<fieldset>
				<legend>Droits</legend>
				<label for="std">Standard</label>
				<input type="radio" name="droits" value="0" id="std" checked>
				<label for="modo">Modo</label>
				<input type="radio" name="droits" value="1" id="modo">
				<label for="admin">Admin</label>
				<input type="radio" name="droits" value="2" id="admin">
			</fieldset>
			<label for="tel">N° de téléphone</label>
			<input type="tel" name="tel" id="tel">
			<input type="submit" value="Envoyer">
		</form>
</body>
</html>
