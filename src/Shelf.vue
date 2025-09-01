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
			<button @click="addBook" class="add-book-btn">Add New Book</button>
		</div>

		<!-- Draggable books using Vue.Draggable -->
		<draggable v-model="books" class="bookshelf-inner" :group="{ name: 'books' }" @start="drag=true" @end="drag=false">
			<div v-for="(book, index) in books" :key="index" class="book">
				<div class="side spine">
					<span class="spine-title">{{ book.title }}</span>
					<span class="spine-author">{{ book.author }}</span>
				</div>
				<div class="side top"></div>
				<div class="side cover"></div>
			</div>
		</draggable>
	</div>
</template>

<script>
// Import Vue.Draggable
import draggable from 'vuedraggable';

export default {
	name: 'Bookshelf',
	components: {
		draggable,
	},
	data() {
		return {
			// Existing books list
			books: [
				{ title: 'The Great Gatsby', author: 'W.S.' },
				{ title: '1984', author: 'W.S.' },
				{ title: 'To Kill a Mockingbird', author: 'W.S.' },
			],
			// New book input values
			newBookTitle: '',
			newBookAuthor: '',
			drag: false, // Track dragging status
		};
	},
	methods: {
		// Method to add a new book to the bookshelf
		addBook() {
			// Check if both title and author are provided
			if (this.newBookTitle && this.newBookAuthor) {
				this.books.push({
					title: this.newBookTitle,
					author: this.newBookAuthor,
				});

				// Clear input fields after adding the book
				this.newBookTitle = '';
				this.newBookAuthor = '';
			} else {
				alert('Please provide both title and author!');
			}
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

.bookshelf {
	width: 100%;
	margin-top: 32px;
	display: flex;
	flex-wrap: wrap;
	gap: 10px; /* Space between books */
}

.bookshelf-inner {
	display: flex;
	flex-wrap: wrap;
	gap: 10px;
	cursor: move; /* Show a move cursor */
}

/* Styling for the input and button form */
.add-book-form {
	margin-bottom: 20px;
	display: flex;
	gap: 10px;
	margin-left: 10px;
}

.input-title,
.input-author {
	padding: 8px;
	font-size: 14px;
	border: 1px solid #ccc;
	border-radius: 4px;
	width: 200px;
}

.add-book-btn {
	padding: 8px 16px;
	background-color: #4CAF50;
	color: white;
	border: none;
	border-radius: 4px;
	cursor: pointer;
}

.add-book-btn:hover {
	background-color: #45a049;
}

/* Book and bookshelf styles */
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
	background-image: var(--spine-tartan);
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
	background-image: url("https://picsum.photos/190/280");
	background-size: contain;
	background-repeat: round;
	left: 50px;
	transform: rotateY(90deg) translateZ(0);
	transition: transform 1s;
}
</style>
