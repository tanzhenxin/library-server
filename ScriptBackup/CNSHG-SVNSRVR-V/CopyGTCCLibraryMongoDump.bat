SET backupMongoDumpPath=C:\LibraryBackup\MongoDumpBackup\MongoDump_%DATE:~10,4%%DATE:~4,2%%DATE:~7,2%_%time:~0,2%%time:~3,2%%time:~6,2%
if not exist "%backupMongoDumpPath%" (md "%backupMongoDumpPath%")
xcopy "\\CN-WANGXUR-2\mongodb-win32-x86_64-2.2.2\bin\dump" "%backupMongoDumpPath%" /S /E /Y /C /F