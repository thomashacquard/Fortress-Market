# Projet ISN Lycée Germaine Tillion 2016-2017: Site web avec acquisition et traitement de données et notifiaction mail.

## Mise en place.

  1. Télécharger le dossier contenant les fichiers source sous [dossier compressé](https://github.com/amatokus8669/Fortress-Market/archive/master.zip)
  
  2. Dans ce dossier se trouve un sous dossier nommé WAMP. C'est ce dossier qu'il faut place dans le dossier www du serveur wamp.
  
## Configuration de WAMP.

  1. cliquer gauche sur l'icone WAMP en bas à droite du bureau windows. Cliquer sur PHP puis PHP.ini. Un fichier teste s'ouvre.
  2. Appuyer sur Ctrl+F et tapez openssl.
  3. Sur la ligne contenant ";extension=php_openssl.dll" retirer le ";". Vous avez donc: "extension=php_openssl.dll".
  4. Sauvegarder et fermer
  
## Utilisation du site web.

  Vous pouvez désormais utiliser le site web ne l'ouvrant avec le serveur WAMP.
  
## Actualisation des données

  Dans votre navigateur, entrez l'adresse suivante: "http://localhost/nom_du_dossier_contenant_tous_les_fichiers/scriptsphp/acquisitiondonnees.php"
  
  Une page se charge. Dès que son chargement est fini, les données sont actualisées.
