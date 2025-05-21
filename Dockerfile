FROM node:18

WORKDIR /app

# Copia só arquivos de dependência para usar cache do Docker
COPY package*.json ./

# Instala dependências
RUN npm ci

# Copia o restante dos arquivos
COPY . .

# Cria diretórios do nginx, se necessário
RUN mkdir -p /var/log/nginx && mkdir -p /var/cache/nginx

# Comando default
CMD ["npm", "start"]
