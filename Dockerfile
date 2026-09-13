ARG BASE_IMAGE=node:18.18.0-alpine
FROM $BASE_IMAGE AS build

RUN corepack enable

WORKDIR /app

COPY package.json pnpm-lock.yaml ./
RUN pnpm install --frozen-lockfile

COPY tsconfig.json ./
COPY server ./server
RUN pnpm run build


FROM $BASE_IMAGE AS dependencies

RUN corepack enable

WORKDIR /app

COPY package.json pnpm-lock.yaml ./
RUN pnpm install --prod --frozen-lockfile

FROM $BASE_IMAGE AS release

RUN apk add --no-cache tini

WORKDIR /app

COPY --from=dependencies /app/node_modules ./node_modules
COPY --from=build /app/server ./server
COPY public ./public

CMD ["tini", "--", "node", "server/index.js"]
