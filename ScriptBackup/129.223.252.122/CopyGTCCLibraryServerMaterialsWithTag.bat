SET backupDir=E:\GTCC_Backup\Library_%DATE:~10,4%%DATE:~4,2%%DATE:~7,2%_%time:~0,2%%time:~3,2%%time:~6,2%
if not exist "%backupDir%\Repositories" (md "%backupDir%\Repositories")
if not exist "%backupDir%\mongodbBackup" (md "%backupDir%\mongodbBackup")
if not exist "%backupDir%\www" (md "%backupDir%\www")

xcopy "\\CN-WANGXUR-2\Repositories" "%backupDir%\Repositories" /S /E /Y /C /F

xcopy "\\CN-WANGXUR-2\mongodb-win32-x86_64-2.2.2" "%backupDir%\\mongodbBackup" /S /E /Y /C /F

xcopy "\\CN-WANGXUR-2\www" "%backupDir%\\www" /S /E /Y /C /F