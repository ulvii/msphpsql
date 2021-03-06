--TEST--
sqlsrv_has_rows() using a forward and scrollable cursor
--SKIPIF--
--FILE--
<?php

require_once("autonomous_setup.php");

// Connect
$conn = new PDO( "sqlsrv:server=$serverName" , $username, $password); 
$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

// Create database
$conn->query("CREATE DATABASE ". $dbName);
// Create table
$stmt = $conn->query("CREATE TABLE ".$dbName.".[dbo].[php_test_table_1] ([c1_int] int, [c2_varchar] varchar(1000))");	
$query = "INSERT INTO ".$dbName.".[dbo].[php_test_table_1] (c1_int, c2_varchar) VALUES ((990021574), ('>vh~Ö.bÐ*äß/ÄAabýZÃ¢Oüzr£ðAß+|~|OU¢a|U<ßrv.uCB.ÐÜh_î+ãå@üðöã,U+ßvuU:/ý_Öãî/ð|bB|_Zbua©r++BA¢z£.üî¢öåäözÜ¢ßb</üöîã,ZbOhß¢Aåb*öî:r>:aöCrÄ~ýZ¢uªÐö.hhßð*zÜÜß*ãüåýãÄ+åýüüaß¢ÃÐBî@~AZöÃOßC@äoÃuCÜ,ÐÄa:îäÄÖý:h*ouªuåvUz_ArßAªãaãvÐåAUüAB:¢Äz|öub<üZvößüå:ãÄ@r/ZAÄðÄÄvzîv~C/£|ýýbüÖ~£|Öå<Üa~/v@åAz©¢£U_ßhbaÃß,zz<ã¢|<ä©>öAuövÖ>abu,zå,+ß/ü/ª_bbB:ÃC~£ü/O©O©ªAª_,|a¢~ýý/b>ßC@/böîöh>~£ð+Bßr©ÄÐÖßã:bA@:>B:UAbããîÜ~uÜ£îCöÖ£©_ÜßzÐ+ÖýZb,A:<<AA*¢ã@Uî:B<öBîÐ>z.ãîÄzC@©*ä|ã._ßZOäb¢Cßovå+uv.£B~~b£ª|ÖÄîßö>©Ãbb|©©ðA£åO~Ã¢ãüîuvÄÜýUzîOÖ/oOßO*>ªßzÃªÖÐböÄåbîðîÐa~©ßîÄßÐ£<î>å<býUrAA©r£@üÄb_:ãZß_/ou@|ªåü~ACãUO<£îßÄîäbßöhßO©ZvßOBü:Zä<ÄªobbO@ÃÐ_~î|a~ð©+,v+Ð/rÃzuöÖZÐOß/CCÖßý¢:<b£B,Ðß¢oZbð~BüOö,Üö~ð:ß,CCî<Oä,Öãî£ÜCZ~/z~ý©vuzoöß/u©å|£ãÐv¢îhý:ÄoÐrz.ßbr_U*<hCîßÖ_+:hbü*að.Aö_Oª_öB>Bã_ý*ah¢rOÄª,ßo¢¢a|BÖäz</bUabÖðOA.Ðîý,Bhö*Cßuß£o,|ü_,ýÐu_b|ZÜh|<U@~übU¢Uð/o/Ð>U£.B£@Ü,ßAÃ>,ðßß+ßÜ©|Ðr©bCðÐ¢üãz>AßðåÃ>bÄåÄ|Z~äÃ/Cb*£bð_/Ða@~AÜãO+ý*CîîÃzÄöÃa©+@vuz>î>©.Cv>hÃý>©Bä,ö~@~@r,AðCu@Ü,@U*ÐvöÃÃªuã.Öa*uZªoZ/ðÖ©ßv_<ÖvåÜÐÜOÐoðßðÃUýZÐB:+ÄÃã£'))";
$stmt = $conn->query($query);

$stmt = $conn->query("CREATE TABLE ".$dbName.".[dbo].[php_test_table_2] ([c1_int] int, [c2_varchar] varchar(max))");	
$query = "INSERT INTO ".$dbName.".[dbo].[php_test_table_2] (c1_int, c2_varchar) VALUES ((990021574), ('>vh~Ö.bÐ*äß/ÄAabýZÃ¢Oüzr£ðAß+|~|OU¢a|U<ßrv.uCB.ÐÜh_î+ãå@üðöã,U+ßvuU:/ý_Öãî/ð|bB|_Zbua©r++BA¢z£.üî¢öåäözÜ¢ßb</üöîã,ZbOhß¢Aåb*öî:r>:aöCrÄ~ýZ¢uªÐö.hhßð*zÜÜß*ãüåýãÄ+åýüüaß¢ÃÐBî@~AZöÃOßC@äoÃuCÜ,ÐÄa:îäÄÖý:h*ouªuåvUz_ArßAªãaãvÐåAUüAB:¢Äz|öub<üZvößüå:ãÄ@r/ZAÄðÄÄvzîv~C/£|ýýbüÖ~£|Öå<Üa~/v@åAz©¢£U_ßhbaÃß,zz<ã¢|<ä©>öAuövÖ>abu,zå,+ß/ü/ª_bbB:ÃC~£ü/O©O©ªAª_,|a¢~ýý/b>ßC@/böîöh>~£ð+Bßr©ÄÐÖßã:bA@:>B:UAbããîÜ~uÜ£îCöÖ£©_ÜßzÐ+ÖýZb,A:<<AA*¢ã@Uî:B<öBîÐ>z.ãîÄzC@©*ä|ã._ßZOäb¢Cßovå+uv.£B~~b£ª|ÖÄîßö>©Ãbb|©©ðA£åO~Ã¢ãüîuvÄÜýUzîOÖ/oOßO*>ªßzÃªÖÐböÄåbîðîÐa~©ßîÄßÐ£<î>å<býUrAA©r£@üÄb_:ãZß_/ou@|ªåü~ACãUO<£îßÄîäbßöhßO©ZvßOBü:Zä<ÄªobbO@ÃÐ_~î|a~ð©+,v+Ð/rÃzuöÖZÐOß/CCÖßý¢:<b£B,Ðß¢oZbð~BüOö,Üö~ð:ß,CCî<Oä,Öãî£ÜCZ~/z~ý©vuzoöß/u©å|£ãÐv¢îhý:ÄoÐrz.ßbr_U*<hCîßÖ_+:hbü*að.Aö_Oª_öB>Bã_ý*ah¢rOÄª,ßo¢¢a|BÖäz</bUabÖðOA.Ðîý,Bhö*Cßuß£o,|ü_,ýÐu_b|ZÜh|<U@~übU¢Uð/o/Ð>U£.B£@Ü,ßAÃ>,ðßß+ßÜ©|Ðr©bCðÐ¢üãz>AßðåÃ>bÄåÄ|Z~äÃ/Cb*£bð_/Ða@~AÜãO+ý*CîîÃzÄöÃa©+@vuz>î>©.Cv>hÃý>©Bä,ö~@~@r,AðCu@Ü,@U*ÐvöÃÃªuã.Öa*uZªoZ/ðÖ©ßv_<ÖvåÜÐÜOÐoðßðÃUýZÐB:+ÄÃã£'))";
$stmt = $conn->query($query);

$size = 2;
$stmt = $conn->prepare("SELECT * FROM ".$dbName.".[dbo].[php_test_table_1]", array(constant('PDO::ATTR_CURSOR') => PDO::CURSOR_SCROLL,PDO::SQLSRV_ATTR_CURSOR_SCROLL_TYPE => PDO::SQLSRV_CURSOR_BUFFERED,PDO::SQLSRV_ATTR_CLIENT_BUFFER_MAX_KB_SIZE=> $size));	
$attr = $stmt->getAttribute(constant('PDO::SQLSRV_ATTR_CLIENT_BUFFER_MAX_KB_SIZE'));	
echo("Client Buffer Size in KB: $attr\n");
$stmt->execute();	
$numRows = 0;
while ($result = $stmt->fetch())
	$numRows++;
	
echo ("Number of rows: $numRows\n");

$size = 3; 
$stmt = $conn->prepare("SELECT * FROM ".$dbName.".[dbo].[php_test_table_2]", array(constant('PDO::ATTR_CURSOR') => PDO::CURSOR_SCROLL,PDO::SQLSRV_ATTR_CURSOR_SCROLL_TYPE => PDO::SQLSRV_CURSOR_BUFFERED,PDO::SQLSRV_ATTR_CLIENT_BUFFER_MAX_KB_SIZE=> $size));	
$attr = $stmt->getAttribute(constant('PDO::SQLSRV_ATTR_CLIENT_BUFFER_MAX_KB_SIZE'));	
echo("Client Buffer Size in KB: $attr\n");
$stmt->execute();	
$numRows = 0;
while ($result = $stmt->fetch())
	$numRows++;
	
echo ("Number of rows: $numRows\n");

// DROP database
$conn->query("DROP DATABASE ". $dbName) ?: die();
$stmt=null;
$conn=null;
print "Done"
?>

--EXPECT--
Client Buffer Size in KB: 2
Number of rows: 1
Client Buffer Size in KB: 3
Number of rows: 1
Done
