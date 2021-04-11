## Foris/Easy/Support

基于[Illuminate\Support](https://github.com/illuminate/support)包提取的部分自用快捷函数，不建议直接使用

[![Build Status](https://travis-ci.com/itsanr-oris/easy-support.svg?branch=master)](https://travis-ci.com/itsanr-oris/easy-support)
[![codecov](https://codecov.io/gh/itsanr-oris/easy-support/branch/master/graph/badge.svg)](https://codecov.io/gh/itsanr-oris/easy-support)
[![Latest Stable Version](https://poser.pugx.org/f-oris/easy-support/v/stable)](https://packagist.org/packages/f-oris/easy-support)
[![Latest Unstable Version](https://poser.pugx.org/f-oris/easy-support/v/unstable)](https://packagist.org/packages/f-oris/easy-support)
[![Total Downloads](https://poser.pugx.org/f-oris/easy-support/downloads)](https://packagist.org/packages/f-oris/easy-support)
[![License](https://poser.pugx.org/f-oris/easy-support/license)](LICENSE)

## 版本说明

- [x] 移除Arr::unset()函数，可使用Arr::forget()函数替代使用
- [x] 移除Arr::expect()函数，可使用Arr::except()函数替代使用
- [x] 修改部分业务代码逻辑，移除`php-7.0`语法，兼容`php-5.5`语法

## 安装使用

```bash
composer require f-oris/easy-support:^2.0
```

## License

MIT License

Copyright (c) 2019-present F.oris <us@f-oris.me>
