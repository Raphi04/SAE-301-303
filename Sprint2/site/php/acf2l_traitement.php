<?php
namespace App\Models;

use App\Database;

class Traitement {
	
	public function requete(string $sql, array $attributs = null) {
		
    // Récupèrer l'instance de Database
    $this->db = Database::getInstance();

        //Cas des requêtes simples
        return $this->db->query($sql);
    }	

	public function findAll() {
		$query = $this->requete('SELECT * FROM '.$this->table);
		return $query->fetchAll();
	}
	
	public function find(int $id) {
    // Exécuter la requête
    return $this->requete("SELECT * FROM {$this->table} WHERE id = $id")->fetch();
	}
	
	public function findBy(array $criteres) {
		$champs = [];
		$valeurs = [];

		//Boucler pour "éclater le tableau"
		foreach($criteres as $champ => $valeur){
			$champs[] = "$champ = ?";
			$valeurs[]= $valeur;
		}

		//Transformer le tableau en chaîne de caractères séparée par des AND
		$liste_champs = implode(' AND ', $champs);

		// Exécuter la requête
		return $this->requete("SELECT * FROM {$this->table} WHERE $liste_champs", $valeurs)->fetchAll();
	}
	
	public function insert(Model $model) {
		$champs = [];
		$inter = [];
		$valeurs = [];

		//Boucler pour éclater le tableau
		foreach($model as $champ => $valeur){
			
			
			if($valeur !== null && $champ != 'db' && $champ != 'table'){
				$champs[] = $champ;
				$inter[] = "?";
				$valeurs[] = $valeur;
			}
    }

    //Transformer le tableau "champs" en une chaine de caractères
    $liste_champs = implode(', ', $champs);
    $liste_inter = implode(', ', $inter);

    // Exécuter la requête
    return $this->requete('INSERT INTO '.$this->table.' ('. $liste_champs.')VALUES('.$liste_inter.')', $valeurs);
	}
	
	public function delete(int $id){
		return $this->requete("DELETE FROM {$this->table} WHERE id = ?", [$id]);
}
}
