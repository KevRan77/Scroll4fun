<?php
// Fichier avec toutes les routes


//------------------------- Configuration (refresh de la database)--------------------------------------------//

$app->get('/install', function ($request, $response, $args) {
   $this->db;
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule::schema()->dropIfExists('pictures');
    $capsule::schema()->create('pictures', function (\Illuminate\Database\Schema\Blueprint $table){
        $table->increments('id');
        $table->string('title');
        $table->string('photo');
        $table->string('theme');

        // Inclut created_at & updated_at
        $table->timestamps();     

    });   
        //Tableaux avec les infos pour pré-remplir la database
        $Tablo = array("Image Game","Image Animal","Image SuperHero","Image Food","Image Work","Image Other");
        $Tablo_image = array("game.jpg","animal.jpg","superhero.jpg","food.jpg","work.jpeg","sport.jpg");
        $Tablo_theme = array("Game", "Animal","SuperHero", "Food", "Work", "Other");
        
        $pictureVar = new pictures;
        //Boucle for qui remplit la database
        for($counter=0;$counter<=5;$counter++){
            ${$counter.$pictureVar}=new pictures;
            ${$counter.$pictureVar}->title=$Tablo[$counter];
            ${$counter.$pictureVar}->photo=$Tablo_image[$counter];
            ${$counter.$pictureVar}->theme=$Tablo_theme[$counter];
            ${$counter.$pictureVar}->save();               
        }    

    //Retourne l'index de base
    return $response->withStatus(302)->withHeader('Location', '/');
});


//------------------------- Vue principale (index)--------------------------------------------//

$app->get('/', function ($request, $response, $args) {
    // Sample log message
    //$this->logger->info("Slim-Skeleton '/' route");

    $this->db;
    $pictures=pictures::all();

    // Retourne la vue index
    return $this->renderer->render($response, 'index.phtml', ['pictures'=>$pictures]);
});


//------------------------- Vue Theme -------------------------------------------//

$app->get('/theme', function ($request, $response, $args) {
    
    $this->db;
    $pictures=pictures::all();

    // Retourne la vue thème
    return $this->renderer->render($response, 'theme.phtml', ['pictures'=>$pictures]);
});


//------------------------- Vue Theme - Game -------------------------------------------//

$app->get('/theme/game', function ($request, $response, $args) {
    
    $this->db;
    $pictures=pictures::all();

    // Retourne la vue game 
    return $this->renderer->render($response, 'game.phtml', ['pictures'=>$pictures]);
});


//------------------------- Vue Theme - Animal -------------------------------------------//

$app->get('/theme/animal', function ($request, $response, $args) {
    
    $this->db;
    $pictures=pictures::all();

    // Retourne la vue animal
    return $this->renderer->render($response, 'animal.phtml', ['pictures'=>$pictures]);
});

//------------------------- Vue Theme - SuperHero -------------------------------------------//

$app->get('/theme/superhero', function ($request, $response, $args) {

    $this->db;
    $pictures=pictures::all();

    // Retourne la vue superhero
    return $this->renderer->render($response, 'superhero.phtml', ['pictures'=>$pictures]);
});

//------------------------- Vue Theme - Other -------------------------------------------//

$app->get('/theme/other', function ($request, $response, $args) {

    $this->db;
    $pictures=pictures::all();

    // Retourne la vue other
    return $this->renderer->render($response, 'other.phtml', ['pictures'=>$pictures]);
});

//------------------------- Vue Theme - Work -------------------------------------------//

$app->get('/theme/work', function ($request, $response, $args) {

    $this->db;
    $pictures=pictures::all();

    // Retourne la vue work
    return $this->renderer->render($response, 'work.phtml', ['pictures'=>$pictures]);
});

//------------------------- Vue Theme - Food -------------------------------------------//

$app->get('/theme/food', function ($request, $response, $args) {

    $this->db;
    $pictures=pictures::all();

    // Retourne la vue food
    return $this->renderer->render($response, 'food.phtml', ['pictures'=>$pictures]);
});


//------------------------- Formulaire de téléchargement--------------------------------------------//

$app->get('/form', function ($request, $response, $args) {

    // Retourne la vue form (formulaire permettant l'upload d'une image)
    return $this->renderer->render($response, 'form.phtml', $args);
});


//------------------------- Afficher une seule image (show)--------------------------------------------//

$app->get('/show/[{id}]', function ($request, $response, $args) {

    $this->db;
    $pictures = pictures::where('id', $args['id'])->get();

    //Retourne la vue show qui permet d'afficher une seule image
    return $this->renderer->render($response, 'show.phtml', ['pictures'=>$pictures]);
});


//------------------------- Supprimer une image (delete)--------------------------------------------//

$app->get('/delete/[{id}]', function ($request, $response, $args) {

    $this->db;
    $pictures = pictures::where('id', $args['id'])->delete(); //Supprime l'image correspondant à l'id
    $pictures=pictures::all();

    //Retourne la vue index de base
    return $response->withStatus(302)->withHeader('Location', '/');
});



//------------------------- Modifier une image (update)--------------------------------------------//

$app->post('/edit/[{id}]', function ($request, $response, $args) {

    $files = $request->getUploadedFiles();
    $param = $request->getParam('title');
    $theme = $request->getParam('theme');
        if (empty($files['file'])) {
            throw new Exception('Expected a newfile');
        }

        $this->db;

        $newfile = $files['file'];

        if ($newfile->getError() === UPLOAD_ERR_OK) {
            $uploadFileName = $newfile->getClientFilename();
            $newfile->moveTo("public/uploads/$uploadFileName");
        }

        $pictures = pictures::where('id', $args['id'])->update(['photo'=>$uploadFileName]);
        $pictures = pictures::where('id', $args['id'])->update(['title'=>$param]);
        $pictures = pictures::where('id', $args['id'])->update(['theme'=>$theme]);
        $pictures=pictures::all();

    // Retourne la vue index de base
    return $response->withStatus(302)->withHeader('Location', '/');
}); 

//------------------------- Télécharger une image (upload)--------------------------------------------//

$app->post('/upload', function ($request, $response, $args) {
   
    $files = $request->getUploadedFiles();
    $param = $request->getParam('title');
    $theme = $request->getParam('theme');
    
    if (empty($files['file'])) {
        throw new Exception('Expected a newfile');
    }

    $this->db;
    
    $newfile = $files['file'];
    if ($newfile->getError() === UPLOAD_ERR_OK) {
        $uploadFileName = $newfile->getClientFilename();
        $newfile->moveTo("public/uploads/$uploadFileName");

        $image = new pictures; 
        $image->photo = $uploadFileName;
        $image->title = $param;
        $image->theme = $theme;
        $image->save();       
    }

    $pictures=pictures::all();

    // Retourne la vue index de base
    return $response->withStatus(302)->withHeader('Location', '/');
});


