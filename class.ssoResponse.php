<?php
/*
 * SSO_Supinfo_PHP V1.0 
 * Auteurs : HUYNH Eddy <Eddy.HUYNH@supinfo.com> et CHEVALIER Alexis <Alexis.CHEVALIER@supinfo.com>
 * Cette classe sert à interpréter la réponse du serveur d'indentification supinfo.
*/

/*

Format de l'array fetchedData :

[identity] => Lien de connexion SSO
[fullname] => Prénom, NOM de l'identifiant
[PersonRole] => Array
    (
        [0] => Statut de l'identifiant (N/A - Student - SCT - Alumni - Staff)
    )

[PersonCampus] => Array
    (
        [0] => Campus de l'identifiant (ID Etablissement;Nom du campus;)
    )

[PersonClasse] => Array
    (
        [0] => Lettre de la classe (A - B - C - D - E) (Ceci est utile pour les grosse promo, ex : B1-A)
    )

[PersonCursus] => Array
    (
        [0] => Cursus de l'identifiant (N/A - cursus;classe;specialisation (ex SIIT;2;WorldWide;))
    )

[PersonRank] => Array
    (
        [0] => Rang de l'identifiant (N/A - Discover - Advanced) (Contient peut être l'historique des rangs)
        [1, etc..] => 
    )

[FullProfSubjects] => Array -> Il y aura des informations si vous êtes un full prof
    (
    )

[TeacherSubjects] => Array -> Il y aura des informations si vous êtes STA
    (
    )
*/
class ssoResponse{
	var $fetchedData;

	//Fonction d'initialisation, transforme les données brutes en données triées
	public function setGetData($get){
		$fields = explode(',', $get['openid_signed']);
		$profil = array();
		$profil['identity'] =  $get['openid_identity'];
		$profil['fullname'] =  $get['openid_sreg_fullname'];
		foreach ($fields as $key => $value) {
			if(preg_match('/^alias3.type/', $value))
			{	
				$actualValue=$get['openid_' . str_replace('.', '_', $value)];
				$profil[$actualValue]=array();
			}
			else if(preg_match('/^alias3.value/', $value))
			{
				$profil[$actualValue][] = $get['openid_' . str_replace('.', '_', $value)];
			}

		}
		$this->fetchedData = $profil;
	}

	public function getFetchedData(){
		return $this->fetchedData;
	}

	public function isInClass($letter){
		if($this->fetchedData['PersonClasse'][0] == $letter)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function isInCursus($level, $spe='WorldWide', $cursus='SIIT'){
		$string = $cursus . ';' . $level . ';' . $spe . ';';
		if($this->fetchedData['PersonCursus'][0] == $string)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function isInCampus($id, $name){
		$string = $id . ';' . $name . ';';
		if($this->fetchedData['PersonCampus'][0] == $string)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function isRole($role){
		if($this->fetchedData['PersonRole'] == $role)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function isRank($rank){
		if(end($this->fetchedData['PersonRank']) == $rank)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>