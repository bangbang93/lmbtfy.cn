ARG BASE_IMAGE=node:18.18.0-alpine
FROM $BASE_IMAGE AS build

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY tsconfig.json ./
COPY server ./server
RUN npm run build


FROM $BASE_IMAGE AS dependencies

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --production

FROM $BASE_IMAGE AS release

RUN apk add --no-cache tini

WORKDIR /app

COPY --from=dependencies /app/node_modules ./node_modules
COPY --from=build /app/server ./server
COPY public ./public

CMD ["tini", "--", "node", "server/index.js"]
