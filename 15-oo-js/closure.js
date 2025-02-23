function outer() {
  count = 0;
  return function inner() {
    count++;
    return count;
  };
}


const a = outer(); //?
a(); //?
a(); //?
a(); //?

const b = outer(); //?
b(); //?
b(); //?

const c = new outer(); //?
c(); //?
c(); //?
c(); //?
c(); //?
