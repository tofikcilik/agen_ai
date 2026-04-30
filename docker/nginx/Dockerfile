FROM node:22-alpine AS frontend-build

WORKDIR /app

COPY frontend-web/package.json /app/package.json
COPY frontend-web/.env.example /app/.env.production

RUN npm install

COPY frontend-web /app

ENV VITE_API_BASE_URL=/api

RUN npm run build

FROM nginx:1.27-alpine

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY --from=frontend-build /app/dist /usr/share/nginx/html
