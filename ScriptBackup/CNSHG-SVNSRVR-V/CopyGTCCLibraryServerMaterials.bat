if not exist "C:\LibraryBackup\Repositories" (md "C:\LibraryBackup\Repositories")
if not exist "C:\LibraryBackup\mongodbBackup" (md "C:\LibraryBackup\mongodbBackup")
if not exist "C:\LibraryBackup\www" (md "C:\LibraryBackup\www")

xcopy "\\CN-WANGXUR-2\Repositories" "C:\LibraryBackup\Repositories" /S /E /Y /C /F

xcopy "\\CN-WANGXUR-2\mongodb-win32-x86_64-2.2.2" "C:\LibraryBackup\mongodbBackup" /S /E /Y /C /F

xcopy "\\CN-WANGXUR-2\www" "C:\LibraryBackup\www" /S /E /Y /C /F