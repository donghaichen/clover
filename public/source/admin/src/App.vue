<template>
  <div id="app">
    <router-view/>
  </div>
</template>

<style>
  body {
    font-family: 'Avenir', Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }
  li, ul, p, div, body, html, table {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  html, body {
    height: 100%;
    overflow: hidden;
  }
  li {
    list-style: none;
  }
  #app {
    height: 100%;
  }
  .box {
    display: flex;
    justify-content: center; /* 水平居中 */
    align-items: center;     /* 垂直居中 */
    width: 100%;
    height: 100%;
    background-color: #FFFFFF;
  }
  .msg {
    text-align: center;
    line-height: 1.4;
    box-sizing: border-box;
  }
  .msg-area {
    margin-bottom: 32px;
  }
  .msg-title {
    margin-bottom: 16px;
    font-weight: 700;
    font-size: 22px;
    word-wrap: break-word;
    word-break: break-all;
  }
  .msg-desc {
    font-size: 17px;
    color: rgba(0, 0, 0, 0.9);
    word-wrap: break-word;
    word-break: break-all;
    margin-bottom: 16px;
  }
</style>
