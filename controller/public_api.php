<?php

class PublicApiController {
  public bool $json = false;
  public bool $get = true;
  public bool $post = false;

  public function fetch_pictures() {
    $db = database();
    $offset = 0;
    $limit = 10;
    if(isset($_GET['offset'])) {
      $offset = $_GET['offset'];
    }
    if(isset($_GET['limit'])) {
      $limit = $_GET['limit'];
    }

    $stmt = $db->prepare("SELECT * FROM ".DATABASE_TABLE_PREFIX."picture_groups ORDER BY CreateTime DESC LIMIT ? OFFSET ?;");
    $stmt->bind_param('ii', $limit, $offset);

    if(!$stmt->execute())
      return response_code(500);
    
    $result = $stmt->get_result();
    if($result->num_rows == 0)
      return response_json([]);

    $groups = [];
    $ids = [];
    while($row = $result->fetch_assoc()) {
      array_push($ids, $row['PictureGroupId']);
      $groups[$row['PictureGroupId']] = [
        'title' => $row['Title'],
        'description' => $row['Description'],
        'date' => $row['CreateTime'],
        'images' => [],
      ];
    }

    $istmt = $db->prepare("SELECT * FROM ".DATABASE_TABLE_PREFIX."pictures WHERE PictureGroupId IN (?".str_repeat(',?', count($ids) - 1).");");
    $istmt_params = str_repeat('i', count($ids));
    $istmt->bind_param($istmt_params, ...$ids);
    if(!$istmt->execute())
      return response_code(500);

    $result = $istmt->get_result();
    while($row = $result->fetch_assoc()) {
      array_push($groups[$row['PictureGroupId']]['images'], PICUTRE_URL_LOCATION.'/'.$row['FileName']);
    }

    $json = array_values($groups);
    return response_json($json);
  }

  public function post_count() {
    $db = database();
    $stmt = $db->prepare("SELECT Count(*) FROM ".DATABASE_TABLE_PREFIX."picture_groups;");
    if(!$stmt->execute())
      return response_code(500);

    $result = $stmt->get_result();
    $postCount = $result->fetch_row()[0];
    return response_json([ 'count' => $postCount ]);
  }
}

