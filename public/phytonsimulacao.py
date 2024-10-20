import os
import time

# Função para criar arquivos falsos como simulação
def create_files():
    for i in range(5):  # Cria 5 arquivos de texto
        filename = f"arquivo_falso_{i}.txt"
        with open(filename, 'w') as f:
            f.write("Este é um arquivo criado por um 'vírus'.\n")
        print(f"[Simulação]: Arquivo {filename} criado.")
        time.sleep(1)

# Função para simular atividades contínuas
def simulate_activity():
    print("[Simulação]: Vírus está ativo no sistema.")
    for _ in range(3):
        print("Vírus rodando... Isso poderia ser um comportamento malicioso!")
        time.sleep(2)

# Função para simular a exibição de mensagens repetidas
def display_warning():
    for i in range(3):
        print(f"[Simulação]: Alerta! Este é um comportamento suspeito ({i+1})")
        time.sleep(1)

# Função principal que executa a "simulação de vírus"
def virus_simulation():
    print("Iniciando simulação de um vírus...")
    time.sleep(2)
    display_warning()
    simulate_activity()
    create_files()
    print("[Simulação]: Fim do 'vírus'. Nenhum dano real foi causado.")

# Executa a simulação
if __name__ == "__main__":
    virus_simulation()
