import bodyParser from 'body-parser'
import {config} from 'dotenv'
import express, {NextFunction, Request, Response} from 'express'
import Knex from 'knex'
import nanoid from 'nanoid'
import {join} from 'path'

config()

const app = express()
const knex = Knex({
  client: 'mysql2',
  connection: {
    host: process.env.MYSQL_HOST,
    user: process.env.MYSQL_USERNAME,
    password: process.env.MYSQL_PASSWORD,
    database: process.env.MYSQL_DATABASE,
  }
})

app.use(bodyParser.json())
app.use(bodyParser.urlencoded({extended: true}))
app.use(express.static(join(__dirname, '../public')))

interface ITable {
  id: number
  uniqId: string
  keyword: string
  open: number
  lastActivity: Date
}

const table = () => knex<ITable>('short_url')

app.post('/s/create', async (req: Request, res: Response, next: NextFunction) => {
  try {
    const keyword = req.body.keyword
    if (!keyword) {
      return res.redirect('/')
    }
    let url = await table().where('keyword', keyword).first()
    if (url) {
      return res.json(url)
    }
    let id = nanoid(10)
    while(await table().where('uniqId', id).first()) {
      id = nanoid(10)
    }
    await table().insert({
      uniqId: id, keyword,
    })
    url = await table().where('uniqId', id).first()
    res.json(url)
  } catch (e) {
    next(e)
  }
})

app.get('/s/:id', async (req: Request, res: Response, next: NextFunction) => {
  try {
    const uniqId = req.params.id
    const url = await table().where('uniqId', uniqId).first()
    if (!url) {
      return res.redirect('/')
    } else {
      return res.redirect(`/?q=${url.keyword}`)
    }
  } catch (e) {
    next(e)
  }
})

app.use(express.static(join(__dirname, '../public')))

app.listen(parseInt(process.env.PORT || '3000', 10), () => {
  console.log('listening on port 3000')
})
