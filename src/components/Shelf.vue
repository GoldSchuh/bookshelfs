<!--// TODO Only do a 'boble' effect on hover-->
<!--// TODO Add spine animation on select-->
<!-- TODO Put book-add functionality in a side bar-->
<!--TODO cover image-->
<!-- TODO Persistent books-->
<!-- TODO CRUD books-->
<!--Resize books and give them colours (zijdelings ekaft)-->
<!--Link book to an actual file-->
<!--Make it possible to show big cover//boek draaien by default-->

<template>
	<div class="bookshelf">
<!--    &lt;!&ndash; Form to add a new book &ndash;&gt;-->
<!--    <div class="add-book-form">-->
<!--      <input-->
<!--          v-model="newBookTitle"-->
<!--          type="text"-->
<!--          placeholder="Enter Book Title"-->
<!--          class="input-title"-->
<!--      />-->
<!--      <input-->
<!--          v-model="newBookAuthor"-->
<!--          type="text"-->
<!--          placeholder="Enter Book Author"-->
<!--          class="input-author"-->
<!--      />-->
<!--      <button @click="addBook" class="book-add">Add</button>-->
<!--    </div>-->

		<!-- Draggable books using Vue.Draggable -->
		<Draggable v-model="books" class="bookshelf-inner" item-key="id" :group="{ name: 'books' }" @start="drag=true" @end="drag=false">
      <template #item="{ element }">
      <div class="book">
				<div class="side spine">
					<span class="spine-title">{{ element.title }}</span>
					<span class="spine-author">{{ element.author }}</span>
				</div>
				<div class="side top"></div>
				<div class="side cover" ></div>
<!--    :style="{ backgroundImage: `url(${element.cover})` }"    -->
			</div>
      </template>
		</Draggable>
	</div>
</template>

<script>
import Draggable from 'vuedraggable'
import {loadState} from "@nextcloud/initial-state";

let nextId = 4

export default {
  name: 'Bookshelf',
  components: { Draggable },

  data() {
    let state = loadState('bookshelfs', 'bookshelfs-initial-state')
    console.log("Loaded state:", state)
    return {
      books: [
        { id: 1, title: 'The Great Gatsby', author: 'W.S.', cover: "/img/object-oriented-reengineering.png" },
        { id: 2, title: '1984', author: 'W.S.', cover: "/img/object-oriented-reengineering.png" },
        { id: 3, title: 'To Kill a Mockingbird', author: 'W.S.', cover: "/img/object-oriented-reengineering.png" },
      ],
      newBookTitle: '',
      newBookAuthor: '',
      drag: false,
    }
  },

  methods: {
    addBook(title, author) {
      if (!title || !author) return

      this.books.push({
        id: nextId++,
        title: title,
        author: author,
      })

      // this.newBookTitle = ''
      // this.newBookAuthor = ''
    },
  },
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
	background-image: url("http://nextcloud.local/apps-extra/bookshelfs/img/object-oriented-reengineering.png"); //"../img/object-oriented-reengineering.png" not work but // http://nextcloud.local/apps-extra/bookshelfs/img/object-oriented-reengineering.png does resolve but this not? Why is it 'apps-extra'?
	background-size: contain;
	background-repeat: round;
	left: 50px;
	transform: rotateY(90deg) translateZ(0);
	transition: transform 1s;
}
</style>
