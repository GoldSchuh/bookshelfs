<!--
  - SPDX-FileCopyrightText: 2026 Kars van Velzen
  - SPDX-FileCopyrightText: 2022 Petar Gyurov
  - SPDX-FileCopyrightText: 2022 Roy Moore
  - SPDX-License-Identifier: AGPL-3.0-or-later
  - SPDX-License-Identifier: MIT
-->

<template>
	<div class="bookshelf">
		<Draggable :list="books" class="bookshelf-inner" item-key="id" @start="drag=true" @end="onDragEnd">
      <template #item="{ element : book }">
      <div class="book" @click="select(book)" @dblclick="openInNewTab(book)">
				<div class="side spine" :style="{height: `${book.height}px`, top: `${280 - book.height}px`, backgroundImage: 'var(--spine-'+`${getPattern(book)}`+')', backgroundColor: `${book.colour}` }">
					<span class="spine-title">{{ book.title }}</span>
					<span class="spine-author">{{ book.author }}</span>
				</div>
				<div class="side top" :style="{top: `${280 - book.height}px`}"></div>
        <div class="side cover" :style="coverStyle(book)"></div>
      </div>
      </template>
		</Draggable>
	</div>
</template>

<script lang="ts">
import Draggable from 'vuedraggable'
import {type Book} from "../models/Book.ts";
import { generateUrl } from '@nextcloud/router'
import type {PropType} from "vue";
import {getPath} from "../utils.ts";

export default {
  components: {
    Draggable,
  },
  props: {
    books: {
      type: Array as PropType<Book[]>,
      required: true,
    }
  },
  data() {
    return {
      drag: false,
    }
  },
  methods: {
    getPath,
    coverStyle(book: Book) {
      const style: Record<string, string> = {
        backgroundColor: book.colour,
        height: `${book.height}px`,
        top: `${280 - book.height}px`,
      }
      const path = getPath(book)
      if (path !== null) {
        style.backgroundImage = `url(${path})`
      }
      return style
    },
    getPattern(book: Book) {
      const availablePatterns = [
        "pyramid",
        "stairs",
        "argyle",
        "tartan",
      ];
      return availablePatterns[book.pattern];
    },
    onDragEnd(evt: { oldIndex: number; newIndex: number }) {
      this.drag = false
      // Draggable changes the array itself, so we only need to update positions of affected books
      for (let i = Math.min(evt.oldIndex, evt.newIndex); i <= Math.max(evt.oldIndex, evt.newIndex); i++) {
        let book = this.books.at(i)
        if(!book) continue;
        book.position = this.books.indexOf(book)
        const options = {
          id: book.id,
          position: book.position
        }
        this.$emit('updateBook',options, false)
      }
    },
    select(book: Book) {
      this.$emit('select', book)
    },
    deleteBook(book: Book) {
      this.$emit('deleteBook', book)
    },
    openInNewTab(book: Book) {
      const url = generateUrl(`/f/${book.file}`)
      window.open(url, '_blank')?.focus();
    }
  }
}
</script>

<style scoped lang="scss">
$title: gold;
$author: goldenrod;

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
  margin-inline-start: 1px;
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
  inset-inline-start: 0;
  left: 0;
	font-size: 12px;
	color: $title;
	writing-mode: vertical-rl;
	text-orientation: mixed;
}

.spine-author {
	position: absolute;
	color: $author;
	bottom: 0;
  inset-inline-start: 20%;
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
  inset-inline-start: 50px;
	transform: rotateY(90deg) translateZ(0);
	transition: transform 1s;
}
</style>
