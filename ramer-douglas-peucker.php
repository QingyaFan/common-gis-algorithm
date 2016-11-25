<?php

/**
 * Ramer-Douglas-Peucker 算法实现
 * @author qingyafan@163.com
 */
class RamerDouglasPeucker
{
  // 要保留的点的索引组成的数组
  public $keptPointArr;

  function __construct()
  {
    $this->keptPointArr = array();
  }

  // 计算给定线段和点之间的距离
  // @param {array} $line 两个点坐标组成的数组
  // @param {array} $point 点坐标，两个 double 组成的数组
  // @return {double} distance
  private function linePointDistance ($line, $point)
  {
    $lineS = $line[0];
    $lineE = $line[1];
    $numerator = abs(
      ($lineE[1]-$lineS[1])*$point[0] - ($lineE[0]-$lineS[0])*$point[1] + $lineE[0]*$lineS[1] - $lineE[1]*$lineS[0]
    );
    $denominator = sqrt(pow($lineE[1]-$lineS[1], 2) + pow($lineE[0]-$lineS[0], 2));
    // 如果分母为零，那么可能是应为线有很多相同坐标的点组成
    // 这种情况下，返回最小距离 0，这样可以使算法删除相同坐标的冗余点
    if ($denominator == 0) {
      return 0;
    }
    return $numerator/$denominator;
  }

  // 拉默-道格拉斯-普克 算法实现
  // @param {array} $lineArr 坐标组成的数组
  // @param {float} $epsilon 点到直线距离阈值
  public function RDP ($lineArr, $epsilon)
  {
    // global $keptPointArr;
    // 线段长度必须大于3
    $pointsNum = count($lineArr);
    if ($pointsNum < 3) {
      return;
    }
    // 递归调用
    $dmax = 0;
    $index = 0;
    for ($i = 1; $i<($pointsNum-1); $i++) {
      $startPoint = $lineArr[0];
      $endPoint = $lineArr[$pointsNum-1];
      $distance = $this->linePointDistance([$startPoint, $endPoint], $lineArr[$i]);
      if ($dmax < $distance) {
        $dmax = $distance;
        $index = $i;
      }
    }
    if ($dmax > $epsilon) {
      array_push($this->keptPointArr, $lineArr[$index]);
      $this->RDP(array_slice($lineArr, 0, $index+1), $epsilon);
      $this->RDP(array_slice($lineArr, $index, $pointsNum-$index), $epsilon);
    } else {
      array_push($this->keptPointArr, $lineArr[0]);
      array_push($this->keptPointArr, $lineArr[$pointsNum-1]);
      return;
    }
  }
}

?>
