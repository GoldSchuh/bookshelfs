<!--// TODO Only do a 'boble' effect on hover-->
<!--// TODO Add spine animation on select-->
<!-- TODO Put book-add functionality in a side bar-->
<!--TODO cover image-->
<!-- TODO Persistent books-->
<!-- TODO CRUD books-->

<template>
	<div class="bookshelf">
		<!-- Form to add a new book -->
		<div class="add-book-form">
			<input
				v-model="newBookTitle"
				type="text"
				placeholder="Enter Book Title"
				class="input-title"
			/>
			<input
				v-model="newBookAuthor"
				type="text"
				placeholder="Enter Book Author"
				class="input-author"
			/>
			<button @click="addBook" class="book-add">Add</button>
		</div>

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

let nextId = 4

export default {
  name: 'Bookshelf',
  components: { Draggable },

  data() {
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
    addBook() {
      if (!this.newBookTitle || !this.newBookAuthor) return

      this.books.push({
        id: nextId++,
        title: this.newBookTitle,
        author: this.newBookAuthor,
      })

      this.newBookTitle = ''
      this.newBookAuthor = ''
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
	margin: 10px;
	display: flex;
	flex-wrap: wrap;
}

/* Input */
.add-book-form {
	margin-bottom: 10px;
	display: flex;
	gap: 5px;
}
.input-title,
.input-author {
	padding: 8px;
	font-size: 14px;
	border: 1px solid #ccc;
	border-radius: 4px;
	width: 20vw;
  max-width: 160px;
  max-height: 16px;
}
.book-add {
	color: white;
  border: 1px solid #ccc;
  border-radius: 4px;
	cursor: pointer;
  max-height: 16px;
}
.book-add:hover {
	background-color: #45a049;
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
	background-image: url("../img/object-oriented-reengineering.png");
	background-size: contain;
	background-repeat: round;
	left: 50px;
	transform: rotateY(90deg) translateZ(0);
	transition: transform 1s;
}
</style>
