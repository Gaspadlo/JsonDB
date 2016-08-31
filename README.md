# JsonDB
I have made changes to the original Classes.

JsonDB.php: now stores tables(json files) in db folder instead of trimming path and saving json files as "DBnameTABLEname.json"

JsonTable.php: Under heavy usage of reading, file locking could sometimes destroy original json files (because even reading tables Opened and Resaved json files - don't ask me why)
I have moved lockFile() to execute only during functions, that in fact really modify original files (not just read them)
