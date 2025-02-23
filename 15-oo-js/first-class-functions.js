function fn1() {
  this //?
}
fn1()

const fn2 = function() {
  this //?
}
fn2()

const fn3 = function() {
  this //?
}
new fn3()


class cls {
  constructor() {
    this //?
  }
}
new cls();

const ar1 = () => {
  this //?
}
ar1()

this.x = 2;

const ar2 = () => {
  this //?
}
ar2()

const ar3 = () => {
  this //?
}
new ar3()

///////////////////////////////////////////////////////////////////////////////

function fn1() {
  this.num = 0;
  this.add = function() {
    return ++this.num;
  }
  return 'ReturnValue';
}

function fn1() {
  this.num = 0;
  this.add = () => ++this.num;
  return 'ReturnValue';
}

const fn1 = function fn2() {
  this.num = 0;
  this.add = () => ++this.num;
  return 'ReturnValue';
};

class fn1 {
  constructor() {
    this.num = 0;
    this.add = function() {
      return ++this.num;
    }
  }
}

class fn1 {
  constructor() {
    this.num = 0;
    this.add = () => ++this.num;
    return 'ReturnValue';
  }
}

class fn1 {
  num = 0;
  add = function() {
    return ++this.num;
  }
}

class fn1 {
  num = 0;
  add = () => ++this.num;
}

class fn1 {
  num = 0;
  add() {
    return ++this.num;
  }
}

/*----------------------------------------------------------------------------*/

const f1 = fn2();    //?

const f2 = fn1();    //?

const a = new fn1(); //?
a.num                //?
a.add()              //?
a.add()              //?
a.add()              //?

const b = new fn1(); //?
b.num                //?
b.add()              //?
