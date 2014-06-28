SET backupMongoDumpPath=E:\GTCC_Backup\Library\MongoDumpBackup\MongoDump_%DATE:~10,4%%DATE:~4,2%%DATE:~7,2%_%time:~0,2%%time:~3,2%%time:~6,2%
if not exist "%backupMongoDumpPath%" (md "%backupMongoDumpPath%")
xcopy "\\CN-WANGXUR-2\mongodb-win32-x86_64-2.2.2\bin\dump" "%backupMongoDumpPath%" /S /E /Y /C /F

if not exist "E:\GTCC_Backup\Library\Repositories" (md "E:\GTCC_Backup\Library\Repositories")
if not exist "E:\GTCC_Backup\Library\mongodbBackup" (md "E:\GTCC_Backup\Library\mongodbBackup")
if not exist "E:\GTCC_Backup\Library\www" (md "E:\GTCC_Backup\Library\www")

xcopy "\\CN-WANGXUR-2\Repositories" "E:\GTCC_Backup\Library\Repositories" /S /E /Y /C /F

xcopy "\\CN-WANGXUR-2\mongodb-win32-x86_64-2.2.2" "E:\GTCC_Backup\Library\mongodbBackup" /S /E /Y /C /F

xcopy "\\CN-WANGXUR-2\www" "E:\GTCC_Backup\Library\www" /S /E /Y /C /F