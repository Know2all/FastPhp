# FastPhp
I am tired of writing same sql queries repeatedly , If you also tired just check this out !

In this package,there are two files
    1.Config.php
    2.Manager.php

1.Config File
    It conatins database connection object,username,password,database,url etc. And conatins the $conn variable also.

2.Manager File
    In this file,it contains the communication code with mysql datablase


# You must need to know
    
    In this package there are mainly 6 methods they are
        
        1. Create
        2.Update
        3.Delete
        4.FecthAll
        5.FetchOne
        6.Query


# Create()
    
    $model = new Manager($conn);

    It takes $conn object as an argument for instantiated the model for ready to communicate with database.

    $result = $model->create("tableName",$data); it returns an boolean result if the $model->affeted_rows > 0.

    It takes 2 arguments :
        1.tableName
        2.array of data
    
# Update()

    $result = $model->update("tableName",$data,"condition"); it returns a boolean result

    It takes 3 arguments :
        1.tableName
        2.associated array of data
        3.criteria for changes in database

# Delete()

    $result = $model->delete("tableName","condition"); it returns a boolean result

    It take 2 arguments :
        1.tableName
        2.criteria for changes in database

# FetchAll()

    $result = $model->fetchAll("tableName","condition"); it returns an associated array of results

    It take 2 arguments :
        1.tableName
        2.criteria for changes in database

# FetchOne()

    $result = $model->fetchOne("tableName","condition"); it returns an array of data;

    It take 2 arguments :
        1.tableName
        2.criteria for changes in database

# Query()

    $result = $model->Query("write your query"); it returns a result oject of mysqli_query();

    It takes Only one argument is OwnQuery.
