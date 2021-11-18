<?php

include_once __DIR__ .
'/DatabaseConnection.php';


function query($pdo, $sql, $parameters = []){
    $query = $pdo->prepare($sql);
    $query->execute($parameters);
    return $query;
}




function processDates($fields){
    foreach($fields as $key => $value){
        if($value instanceof DateTime){
            $fields[$key] = $value->format('Y-m-d');
        }
    }
    return $fields;
}




function save($pdo, $table, $primaryKey, $record){
    try{
        if($record[$primaryKey] == ''){
            $record[$primaryKey] = null;
        }
        insert($pdo, $table, $record);
    }
    catch(PDOException $e){
        update($pdo, $table, $primaryKey, $record);
    }
}



/* UPDATE */

/* Go back in book and find out how the setting the primaryKey works, understand this before proceeding */

function update($pdo,$table,$primaryKey, $fields){

    $query = ' UPDATE `'.$table.'` SET ';

    foreach($fields as $key => $value){
        $query .= '`'.$key.'` = :'.$key.',';
    }
    //rtrim strips whitespace or other characters from the end of a string
    $query = rtrim($query, ',');
    $query .= ' WHERE `'.$primaryKey.'` = :primaryKey';

    $fields['primaryKey'] = $fields['id'];

    $fields = processDates($fields);


    /*update($pdo,joke,id,[
            'id' => 1,
            'joketext' => 'Why did the programmer quit his job?
                He didn\'t get arrays',
                'authorId' =>1,
                'primaryKey' => 'id'
                        ]) */
    //query($pdo, 'UPDATE `joke` SET `id` = :id, `joketext` = :joketext, `authorId` = :authorId WHERE `id` = :primaryKey');
    //
    query($pdo,$query,$fields);
   
}

function updateJoke($pdo,$fields){
    
    $query = ' UPDATE `joke` SET ';

    foreach($fields as $key => $value){
        $query .= '`'.$key.'` = :'.$key.',';
    }
    //rtrim strips whitespace or other characters from the end of a string
    $query = rtrim($query, ',');
    $query .= ' WHERE `id` = :primaryKey';

    $fields = processDates($fields);

    
    $fields['primaryKey'] = $fields['id'];

    query($pdo,$query,$fields);
   
}

##SELECT##
//retrieving ( SELECT ) is different from update, insert, delete 
function getJoke($pdo, $id){

    $parameters = [':id' => $id];
    
    $query = query($pdo, 'SELECT * FROM `joke` WHERE `id` = :id', $parameters);

    return $query->fetch();
    
}

function findById($pdo, $table, $primaryKey, $value){
    $query = 'SELECT * FROM `'.$table.'` WHERE `'.$primaryKey.'` = :value';

    $parameters = [
        'value' => $value
    ];

    $query = query($pdo, $query, $parameters);

    return $query->fetch();
}

/*  DELETE 
Generic delete function
// Delete author with the ID of 2
delete($pdo, 'author', 'id', 2);
// Delete joke with the id of 5
delete($pdo, 'joke', 'id', 5);
// Delete the book with the ISBN 978-3-16-148410-0
delete($pdo, 'book', '978-3-16-148410-0', 'isbn');*/

function delete($pdo,$table,$primaryKey,$id){
    $parameters = [':id' => $id];
    
    //delete($pdo, 'book', 'isbn', '978-3-16-148410-0')
    //query($pdo, 'DELETE FROM `book` WHERE `isbn` = 978-3-16-148410-0);
    query($pdo, 'DELETE FROM `'.$table.'` WHERE `'.$primaryKey.'` = :id', $parameters);
}

/* RETURN ALL  */

/* new generic function
 Select all the jokes from the database
$allJokes = findAll($pdo, 'joke');
 Select all the authors from the database
$allAuthors = findAll($pdo, 'author'); */
function findAll($pdo,$table){
    $result = query($pdo,'SELECT * FROM `'.$table.'`');

    return $result->fetchAll();
}



 
/* INSERT  */
function insert($pdo,$table,$fields){

   
    $query = 'INSERT INTO `'.$table.'` (';

    foreach($fields as $key => $value){
        $query .= '`'.$key.'`,';
    }
    $query = rtrim($query,',');
    $query .= ') VALUES (';

    foreach($fields as $key => $value){
        $query .= ':'.$key.',';
    }

    $query = rtrim($query,',');
    $query .= ');';

    $fields = processDates($fields);

    query($pdo, $query, $fields);
}

    


/* 
Old functions
function insertJoke($pdo,$fields){

   
    $query = 'INSERT INTO `joke` (';

    foreach($fields as $key => $value){
        $query .= '`'.$key.'`,';
    }
    $query = rtrim($query,',');
    $query .= ') VALUES (';

    foreach($fields as $key => $value){
        $query .= ':'.$key.',';
    }

    $query = rtrim($query,',');
    $query .= ');';

    $fields = processDates($fields);

    query($pdo, $query, $fields);
}



function insertAuthor($pdo, $fields){

    'INSERT INTO `author` (`name`,`email`) VALUES (:name,:email)';

    $query = 'INSERT INTO `author` (';

    foreach($fields as $key => $value){
        $query .= '`'.$key.'`,';
    }
    $query = rtrim($query,',');


    $query .= ') VALUES (';

    foreach($fields as $key => $value){
        $query .= ':'.$key.',';
    }
    $query = rtrim($query,',');
    $query .= ');';

    $fields = processDates($fields);

    
    query($pdo,$query, $fields);

}


function totalJokes($pdo) {

    $query = $pdo->prepare();
    $query->execute(); 
    $row = $query->fetch();
    return $row[0];
    }*/

/* 
old functions
function allJokes($pdo){
    $jokes = query($pdo, 'SELECT `joke`.`id`,`joketext`, `jokedate`, `name`,`email` FROM `joke`
    INNER JOIN `author` ON `authorid` = `author`.`id`');

    return $jokes->fetchAll();
}

function allAuthors($pdo){
    $authors = query($pdo, 'SELECT * FROM `author`');

    return $authors->fetchAll();
}

/* 
Old functions
function deleteJoke($pdo, $id){
    $parameters = [':id' => $id];


    query($pdo, 'DELETE FROM `joke` WHERE `id` = :id', $parameters);
}

function deleteAuthor($pdo,$id){
    $parameters = [':id' => $id];

    query($pdo,'DELETE FROM `author` WHERE `id` = :id', $parameters);
}
 */


