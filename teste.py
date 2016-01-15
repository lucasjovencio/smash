def f_print (n):
    print ("Bem vindo "+n)
   
def main():
    nome = str(input("Digite o seu nome: "))
    print(f_print(nome))
if __name__ == '__main__':
    main()
