<template>
	<div class="bookshelf">
		<Draggable class="bookshelf-inner" v-model="books"  item-key="id" @start="drag=true" @end="onDragEnd()">
      <template #item="{ element : book }">
      <div class="book" @click="select(book)" @dblclick="openInNewTab(book)">
				<div class="side spine" :style="{height: `${book.height}px`, top: `${280 - book.height}px`, backgroundImage: 'var(--spine-'+`${getPattern(book)}`+')', backgroundColor: `${book.colour}` }">
					<span class="spine-title">{{ book.title }}</span>
					<span class="spine-author">{{ book.author }}</span>
				</div>
				<div class="side top" :style="{top: `${280 - book.height}px`}"></div>
        <div class="side cover" :style="{backgroundColor: `${book.colour}`, backgroundImage: `url(${getPath(book)})`, height: `${book.height}px`, top: `${280 - book.height}px`}"></div>
      </div>
      </template>
		</Draggable>
	</div>
</template>

<script lang="ts">
import Draggable from 'vuedraggable'
import {loadState} from "@nextcloud/initial-state";
import {generateOcsUrl} from "@nextcloud/router";
import axios from "@nextcloud/axios";
import {showError} from "@nextcloud/dialogs";
import {translate} from "@nextcloud/l10n";
import {type Book, constructBook} from "../models/Book.ts";
import { generateUrl } from '@nextcloud/router'
import {getRandomHeight, randomColor, randomPattern} from "../utils.ts";

export default {
  name: 'Bookshelf',
  components: {
    Draggable,
  },
  data() {
    let state: any = loadState('bookshelfs', 'bookshelfs-initial-state')
    const books: Book[] = (state.$books || []).sort((a: Book, b: Book) => a.position - b.position);
    return {
      books,
      title: '',
      author: '',
      drag: false,
    }
  },
  methods: {
    getPattern(book: Book) {
      const availablePatterns = [
        "pyramid",
        "stairs",
        "argyle",
        "tartan",
      ];
      return availablePatterns[book.pattern];
    },
    createBook(book: Book) {
      const options = {
        title: book.title,
        author: book.author,
        position: this.books.length,
        url: book.url,
        file: book.file,
        colour: book.colour,
        pattern: book.pattern,
        height: book.height,
      }
      const api = generateOcsUrl('apps/bookshelfs/api/v1/books')
      axios.post(api, options).then(response => {
        this.books.push(constructBook(response.data.ocs.data))
      }).catch((error) => {
        showError(translate('bookshelfs', 'Error adding book'))
        console.error(error)
      })
    }, onDragEnd() {
      this.drag = false
      this.updateBookOrder()
    },
    updateBookOrder() {
      this.books.forEach((book: Book, index: number) => {
        book.position = index
        const url = generateOcsUrl(`apps/bookshelfs/api/v1/books/${book.id}`)
        const options = {
          position: book.position,
        }
        axios.put(url, options).catch((error) => {
          showError(translate('bookshelfs', 'Error updating books'))
          console.error(error)
        })
      })
      this.sort()
    },
    sort() {
      this.books =  this.books.sort((a: Book, b: Book) => a.position - b.position);
    },
    getPath(book: Book) {
      const img_link = `/index.php/core/preview?fileId=${book.url}&x=190&y=280`
      return(img_link)
    },
    select(book: Book) {
      this.$emit('select', book)
    },
    deleteBook(book: Book) {
      const idx = this.books.findIndex((b: Book) => b.id === book.id)
      if (idx !== -1) {
        this.books.splice(idx, 1)
      }
    },
    updateBook(book: Book) {
      const idx = this.books.findIndex((b: Book) => b.id === book.id)
      if (idx !== -1) {
        this.books[idx] = book
      }
    },
    openInNewTab(book: Book) {
      const url = generateUrl(`/f/${book.file}`)
      window.open(url, '_blank')?.focus();
    },
    reStyle() {
      const oldBooks = this.books
      this.reset()
      console.log(oldBooks)
      oldBooks.forEach((book: Book) => {
        book.colour = randomColor();
        book.pattern = randomPattern();
        book.height = getRandomHeight();
        this.createBook(book)
      })
    },
    reset() {
      this.books.forEach((book: Book) => {
        const options = {
          id: book.id
        }
        const api = generateOcsUrl('apps/bookshelfs/api/v1/books/' + book.id)
        // @ts-ignore
        axios.delete(api, options).then(() => {
        }).catch((error) => {
          showError(translate('bookshelfs', 'Error deleting book'))
          console.error(error)
        })
      })
      this.books = []
    }
  }
}
</script>

<style scoped lang="scss">
$color_1: black;
$color_2: gold;
$color_3: goldenrod;

.bookshelf {
  /* Adding --spine styling here to make it available in this scope/component */
  --spine-pyramid: linear-gradient(315deg, transparent 75%, rgba(255,255,255,0.1) 0),
  linear-gradient(45deg, transparent 75%, rgba(255,255,255,0.1) 0),
  linear-gradient(135deg, rgba(255,255,255,0.2) 166px, transparent 0),
  linear-gradient(45deg, rgba(0,0,0,0.1) 75%, transparent 0);
  --spine-pyramid-size: 20px 20px;

  --spine-stairs: repeating-linear-gradient(63deg, rgba(255,255,255,0.1), rgba(255,255,255,0.1) 1px, transparent 3px),
  linear-gradient(127deg, rgba(255,255,255,0.1), rgba(255,255,255,0.1) 90px, transparent 55%),
  linear-gradient(transparent 51%, rgba(0,0,0,0.1) 170px);
  --spine-stairs-size: 70px 120px;

  --spine-argyle: repeating-linear-gradient(120deg, rgba(255,255,255,0.1), rgba(255,255,255,0.1) 1px, transparent 1px, transparent 60px),
  repeating-linear-gradient(60deg, rgba(255,255,255,0.1), rgba(255,255,255,0.1) 1px, transparent 1px, transparent 60px),
  linear-gradient(60deg, rgba(0,0,0,0.1) 25%, transparent 25%);
  --spine-argyle-size: 70px 120px;

  --spine-tartan: repeating-linear-gradient(transparent, transparent 50px, rgba(0,0,0,0.4) 50px, rgba(0,0,0,0.4) 53px, transparent 53px),
  repeating-linear-gradient(270deg, transparent, transparent 50px, rgba(0,0,0,0.4) 50px),
  repeating-linear-gradient(125deg, transparent 2px, rgba(0,0,0,0.2) 2px, rgba(0,0,0,0.2) 3px);
  --spine-tartan-size: 232px 232px;

	width: 100%;
	margin: 50px;
	display: flex;
	flex-wrap: wrap;
}

.bookshelf-inner {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
}

.book {
	width: 50px;
	height: 280px;
	position: relative;
	margin-left: 1px;
  margin-bottom: 5px;
	transform-style: preserve-3d;
	transform: translateZ(0) rotateY(0);
	transition: transform 1s;
}

.book:hover {
	z-index: 1;
	transform: rotateX(-25deg) rotateY(-40deg) rotateZ(-15deg) translateY(50px) translateX(-30px);
}

.side {
	position: absolute;
	border: 2px solid var(--color-border-maxcontrast);
	border-radius: 3px;
	font-weight: bold;
	color: $color_1;
	text-align: center;
	transform-origin: center left;
}

.spine {
	position: relative;
	width: 50px;
	height: 280px;
  transform: rotateY(0deg) translateZ(0px);
}

.spine-title {
	margin: 2px;
	position: absolute;
	top: 0;
	left: 0;
	font-size: 12px;
	color: $color_2;
	writing-mode: vertical-rl;
	text-orientation: mixed;
}

.spine-author {
	position: absolute;
	color: $color_3;
	bottom: 0;
	left: 20%;
}

.top {
	width: 50px;
	height: 190px;
	top: -2px;
	background-image: linear-gradient(90deg, white 90%, gray 10%);
	background-size: 5px 5px;
	transform: rotateX(90deg) translateZ(95px) translateY(-95px);
}

.cover {
	width: 190px;
	height: 280px;
	top: 0;
	background-size: contain;
	background-repeat: round;
	left: 50px;
	transform: rotateY(90deg) translateZ(0);
	transition: transform 1s;
}
</style>
