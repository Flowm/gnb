REQUIREMENTS:
python 3
openpyxl (variables_mapping.py uses an external library to generate an .xlsx file)

INSTALL INSTRUCTIONS:
The openpyxl modules can be installed with the following command:
sudo pip install openpyxl

USAGE:
The script maps the raw assembly variables to a more user-friendly excel table.
The raw variables must be copied from IDA Pro to a variables.txt file.
The required format in order to be parse these variables is:
.text:08048AD1 var_59C         = dword ptr -59Ch
The input variables.txt file must be in the same folder as the variables_mapping.py script.
The output of the script will be a variables.xlsx file, containing the generated mappings.

