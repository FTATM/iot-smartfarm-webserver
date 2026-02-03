<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drag & Drop</title>
    <link rel="stylesheet" href="includes/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
    <style>
body {
  font-family: sans-serif;
}
.columns {
  margin: 0 auto;
  background: #d3d3d3;
  width: 1000px;
  padding: 20px;
}
.card {
  width: 19%;
  display: inline-block;
  margin-right: 1.2%;
  height: 120px;
  font-size: 50px;
  background: #cccccc;
  border: 3px solid #036683;
  box-sizing: border-box;
  color: #fff;
  line-height: 120px;
  vertical-align: middle;
  text-align: center;
  margin: 2px;
/*&:last-child
    margin-right 0
  
  &:nth-child(2)
    background dodgerblue
    border-color dodgerblue
  
  &:nth-child(3)
    background darkturquoise
    border-color darkturquoise
  */
}
.card:nth-child(8) {
  background: #cccccc;
  border-color: rgb(55 48 163);
  width: 59%;
}
.card.dragging {
  opacity: 0.5;
}
.card.over {
  border: 3px dashed #000;
}
[draggable] {
  user-select: none;
}

    </style>
</head>
<body>
<?php
    include_once("nav_bar.php");
    /**
     * https://flowbite.com/docs/plugins/charts/#getting-started
     */

?>
        <h1 class="text-3xl font-bold">Drag &amp; Drop|Config Element Dashboard</h1>

        <div class="columns">
            <div class="card" draggable="true">A</div>
            <div class="card" draggable="true">B</div>
            <div class="card" draggable="true">C</div>
            <div class="card" draggable="true">D</div>
            <div class="card" draggable="true">E</div>
            <div class="card" draggable="true">F</div>
            <div class="card" draggable="true">G</div>
            <div class="card" draggable="true">H</div>
            <div class="card" draggable="true">I</div>
            <div class="card" draggable="true">J</div>
            <div class="card" draggable="true">K</div>
            <div class="card" draggable="true">L</div>
            <div class="card" draggable="true">M</div>
            <div class="card" draggable="true">N</div>
            <div class="card" draggable="true">O</div>
            <div class="card" draggable="true">P</div>
        </div>
        <script>
            var columns = document.querySelectorAll('.card');
            var draggingClass = 'dragging';
            var dragSource;

            Array.prototype.forEach.call(columns, function (col) {
            col.addEventListener('dragstart', handleDragStart, false);
            col.addEventListener('dragenter', handleDragEnter, false)
            col.addEventListener('dragover', handleDragOver, false);
            col.addEventListener('dragleave', handleDragLeave, false);
            col.addEventListener('drop', handleDrop, false);
            col.addEventListener('dragend', handleDragEnd, false);
            });

            function handleDragStart (evt) {
            dragSource = this;
            evt.target.classList.add(draggingClass);
            evt.dataTransfer.effectAllowed = 'move';
            evt.dataTransfer.setData('text/html', this.innerHTML);
            }

            function handleDragOver (evt) {
            evt.dataTransfer.dropEffect = 'move';
            evt.preventDefault();
            }

            function handleDragEnter (evt) {
            this.classList.add('over');
            }

            function handleDragLeave (evt) {
            this.classList.remove('over');
            }

            function handleDrop (evt) {
            evt.stopPropagation();
            
            if (dragSource !== this) {
                dragSource.innerHTML = this.innerHTML;
                this.innerHTML = evt.dataTransfer.getData('text/html');
            }
            
            evt.preventDefault();
            }

            function handleDragEnd (evt) {
            Array.prototype.forEach.call(columns, function (col) {
                ['over', 'dragging'].forEach(function (className) {
                col.classList.remove(className);
                });
            });
            }
        </script>
</body>
</html>