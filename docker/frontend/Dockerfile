FROM node:22-alpine

WORKDIR /app

COPY frontend-web/package.json /app/package.json

RUN npm install

COPY frontend-web /app

CMD ["sh", "-c", "npm install && npm run dev -- --host 0.0.0.0 --port 5173"]
