@echo off
REM Script para subir o projeto para o GitHub

REM Checa se a pasta atual é um repositório Git
git rev-parse --is-inside-work-tree >nul 2>&1
if errorlevel 1 (
    echo Inicializando repositório Git...
    git init
) else (
    echo Repositorio Git ja inicializado.
)

REM Adiciona remoto (tenta remover se já existir para evitar erro)
git remote remove origin >nul 2>&1
echo Adicionando remoto do GitHub...
git remote add origin https://github.com/Enzomine456/Hostcode.git

echo Adicionando arquivos para commit...
git add .

echo Fazendo commit...
git commit -m "Atualizacao do projeto via script"

echo Enviando para o GitHub...
git push -u origin main

pause
