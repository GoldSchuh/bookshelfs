<!--TODO cover image selection EditImageField.vue-->
<!--TODO file link on click-->
<!--Add tests-->
<!--Go Public with big credit note-->

<!-- Only do a 'boble' effect on hover-->
<!-- Add spine animation on select-->
<!--Book search bar-->
<!--Resize books and give them colours (zijdelings ekaft)-->
<!--Link book to an actual file-->
<!--Make it possible to show big cover//boek draaien by default-->
<!--Keep book sorted orders-->
<!-- make moving more efficient? (= move mode?)-->

<template>
	<div class="bookshelf">
		<Draggable class="bookshelf-inner" v-model="books"  item-key="id" @start="drag=true" @end="onDragEnd"> // :group="{ name: 'books' }"
      <template #item="{ element }">
      <div class="book">
				<div class="side spine">
					<span class="spine-title">{{ element.title }}</span>
					<span class="spine-author">{{ element.author }}</span>
				</div>
				<div class="side top"></div>
<!--        TODO translate url to preview-->
        <div class="side cover" :style="{ backgroundImage: `url(${element.url})` }"></div>
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
import {type Book, createBook} from "../models/Book.ts";

export default {
  name: 'Bookshelf',
  components: {Draggable},

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
    addBook(title: string, author: string, url: string, file: number) {
      const options = {
        title,
        author,
        position: this.books.length,
        url,
        file
        //
      }
      const api = generateOcsUrl('apps/bookshelfs/api/v1/books')
      axios.post(api, options).then(response => {
        this.books.push(createBook(response.data.ocs.data))
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
    }
  }
}
</script>

<style scoped lang="scss">
$color_1: black;
$color_2: gold;
$color_3: goldenrod;

:root {
	--spine-pyramid: linear-gradient(315deg, transparent 75%, rgba(255, 255, 255, 0.1) 0),
}

// Main component
.bookshelf {
	width: 100%;
	margin: 50px;
	display: flex;
	flex-wrap: wrap;
}

/* Books */
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
	border: 2px solid black;
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

	//background-image: var(--thisone); // TODO Replace with book image colours from cover
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
	//background-image: url("http://nextcloud.local/apps-extra/bookshelfs/img/object-oriented-reengineering.png"); //"../img/object-oriented-reengineering.png" not work but // http://nextcloud.local/apps-extra/bookshelfs/img/object-oriented-reengineering.png does resolve but this not? Why is it 'apps-extra'?
	background-size: contain;
	background-repeat: round;
	left: 50px;
	transform: rotateY(90deg) translateZ(0);
	transition: transform 1s;
}
</style>
